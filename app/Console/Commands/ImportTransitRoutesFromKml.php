<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TransitRoute;

class ImportTransitRoutesFromKml extends Command
{
    protected $signature = 'flashride:import-routes-kml {file=transit_routes_morelia.kml}';
    protected $description = 'Importa rutas de transporte desde un KML a la tabla transit_routes';

    public function handle()
    {
        $fileName = $this->argument('file');
        $path = storage_path('app/' . $fileName);

        if (! file_exists($path)) {
            $this->error("No se encontró el archivo: {$path}");
            return 1;
        }

        $this->info("Leyendo KML: {$path}");

        // Carga del XML
        $xml = simplexml_load_file($path);
        if (! $xml) {
            $this->error('No se pudo parsear el KML.');
            return 1;
        }

        // Registrar namespace KML
        $namespaces = $xml->getDocNamespaces();
        $kmlNs = $namespaces[''] ?? ($namespaces['kml'] ?? null);
        if ($kmlNs) {
            $xml->registerXPathNamespace('k', $kmlNs);
            $placemarks = $xml->xpath('//k:Placemark');
        } else {
            // KML sin namespace, más sucio pero funciona muchas veces
            $placemarks = $xml->xpath('//Placemark');
        }

        if (! $placemarks) {
            $this->error('No se encontraron Placemarks en el KML.');
            return 1;
        }

        $insertados = 0;

        foreach ($placemarks as $pm) {
            // ---- Datos básicos ----
            $name = (string)($pm->name ?? '');
            $ruta = null;
            $sentido = null;
            $empresa = null;
            $agrupacion = null;
            $longitud = null;
            $idRuta = null;

            if (isset($pm->ExtendedData->SchemaData->SimpleData)) {
                foreach ($pm->ExtendedData->SchemaData->SimpleData as $sd) {
                    $attrName = (string)$sd['name'];
                    $value = trim((string)$sd);

                    switch ($attrName) {
                        case 'ID':
                            $idRuta = $value;
                            break;
                        case 'RUTA':
                            $ruta = $value;
                            break;
                        case 'SENTIDO':
                            $sentido = $value;
                            break;
                        case 'EMPRESA':
                            $empresa = $value;
                            break;
                        case 'AGRUPACION':
                            $agrupacion = $value;
                            break;
                        case 'NOMBRE':
                            $name = $value; // Si quieres usar este como name
                            break;
                        case 'LONGITUD':
                            $longitud = $value;
                            break;
                    }
                }
            }

            // ---- Coordenadas → polyline ----
            $coordinates = null;
            if (isset($pm->LineString->coordinates)) {
                $coordinates = (string)$pm->LineString->coordinates;
            } elseif (isset($pm->MultiGeometry->LineString->coordinates)) {
                // Por si vienen en MultiGeometry
                $coordinates = (string)$pm->MultiGeometry->LineString->coordinates;
            }

            if (! $coordinates) {
                // Sin geometría, la saltamos
                $this->warn("Placemark sin LineString, name={$name}, ID={$idRuta} -> saltando");
                continue;
            }

            $points = $this->parseKmlCoordinates($coordinates);
            if (count($points) < 2) {
                $this->warn("Muy pocos puntos en la ruta name={$name}, ID={$idRuta} -> saltando");
                continue;
            }

            $polyline = $this->encodePolyline($points);

            // ---- Mapeo a transit_routes ----
            $shortName = $ruta ?: $name ?: $idRuta ?: 'RUTA_SIN_NOMBRE';
            $longName = $ruta ?: $name;

            // Color de ejemplo: puedes hacer un switch por empresa/ruta si quieres
            $color = '0080FF';   // azulito por defecto
            $textColor = 'FFFFFF';

            // Evitar duplicados básicos por short_name
            $existing = TransitRoute::where('short_name', $shortName)->first();
            if ($existing) {
                $this->warn("Ya existe transit_route con short_name={$shortName}, actualizando polyline...");
                $existing->polyline = $polyline;
                $existing->save();
                continue;
            }

            TransitRoute::create([
                'short_name'   => $shortName,
                'long_name'    => $longName,
                'vehicle_type' => 'combi',   // o 'bus', según el caso
                'color'        => $color,
                'text_color'   => $textColor,
                'polyline'     => $polyline,
                'stops_json'   => '[]',      // si luego tienes paraderos reales, lo cambiamos
                'is_active'    => 1,
            ]);

            $insertados++;
            $this->info("Insertada ruta: {$shortName}");
        }

        $this->info("Importación terminada. Rutas nuevas insertadas: {$insertados}");

        return 0;
    }

    /**
     * Convierte el string de coordenadas KML a array de puntos [lat, lng]
     */
    private function parseKmlCoordinates(string $coordinates): array
    {
        $points = [];
        // Las coords en KML suelen ir "lon,lat,alt lon,lat,alt ..."
        $chunks = preg_split('/\s+/', trim($coordinates));

        foreach ($chunks as $chunk) {
            if ($chunk === '') {
                continue;
            }
            $parts = explode(',', $chunk);
            if (count($parts) < 2) {
                continue;
            }

            $lng = (float)$parts[0];
            $lat = (float)$parts[1];

            $points[] = [$lat, $lng]; // polyline va en orden lat, lng
        }

        return $points;
    }

    /**
     * Codifica un array de [lat, lng] en polyline (Google Polyline Encoding)
     */
    private function encodePolyline(array $points): string
    {
        $result = '';
        $prevLat = 0;
        $prevLng = 0;

        foreach ($points as [$lat, $lng]) {
            $lat = (int) round($lat * 1e5);
            $lng = (int) round($lng * 1e5);

            $dLat = $lat - $prevLat;
            $dLng = $lng - $prevLng;

            $prevLat = $lat;
            $prevLng = $lng;

            $result .= $this->encodeSignedNumber($dLat);
            $result .= $this->encodeSignedNumber($dLng);
        }

        return $result;
    }

    private function encodeSignedNumber(int $num): string
    {
        $snum = $num << 1;
        if ($num < 0) {
            $snum = ~ $snum;
        }
        return $this->encodeNumber($snum);
    }

    private function encodeNumber(int $num): string
    {
        $encoded = '';

        while ($num >= 0x20) {
            $encoded .= chr((0x20 | ($num & 0x1f)) + 63);
            $num >>= 5;
        }

        $encoded .= chr($num + 63);
        return $encoded;
    }
}
