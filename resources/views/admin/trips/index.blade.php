@extends('layouts.app')
@section('title','Viajes')

@section('content')
  <h1 class="text-xl font-bold mb-4">Viajes</h1>
  <div class="card-glass rounded-xl border border-white/10 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="border-b border-white/10">
        <th class="p-3 text-left">ID</th>
        <th class="p-3 text-left">Conductor</th>
        <th class="p-3 text-left">Origen</th>
        <th class="p-3 text-left">Destino</th>
        <th class="p-3 text-left">Inicio</th>
        <th class="p-3"></th>
      </tr></thead>
      <tbody>
        @forelse($trips as $t)
          <tr class="border-b border-white/10">
            <td class="p-3">{{ $t->id }}</td>
            <td class="p-3">{{ optional($t->driver)->name }}</td>
            <td class="p-3">{{ $t->origin }}</td>
            <td class="p-3">{{ $t->destination }}</td>
            <td class="p-3">{{ $t->started_at }}</td>
            <td class="p-3 text-right">
              <a class="underline" href="{{ route('admin.trips.show',$t) }}">Ver</a>
            </td>
          </tr>
        @empty
          <tr><td class="p-4" colspan="6">Sin viajes.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $trips->links() }}</div>
@endsection
