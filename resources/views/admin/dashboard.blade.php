{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title','Panel Admin')

@section('content')
  <div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-extrabold mb-6">FlashRide · Panel Admin</h1>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <a href="{{ route('admin.users.index') }}" class="card-glass rounded-xl p-5 border border-white/10">Usuarios</a>
      <a href="{{ route('admin.drivers.index') }}" class="card-glass rounded-xl p-5 border border-white/10">Conductores</a>
      <a href="{{ route('admin.vehicles.index') }}" class="card-glass rounded-xl p-5 border border-white/10">Vehículos</a>
      <a href="{{ route('admin.trips.index') }}" class="card-glass rounded-xl p-5 border border-white/10">Viajes</a>
      <a href="{{ route('admin.panic.index') }}" class="card-glass rounded-xl p-5 border border-white/10">Pánico</a>
      <a href="{{ route('admin.assignments.index') }}" class="card-glass rounded-xl p-5 border border-white/10">Asignaciones</a>
      <a href="{{ route('admin.itineraries.index') }}" class="card-glass rounded-xl p-5 border border-white/10">Itinerarios</a>
      <a href="{{ route('admin.payouts.index') }}" class="card-glass rounded-xl p-5 border border-white/10">Pagos</a>
      <a href="{{ route('admin.reports.index') }}" class="card-glass rounded-xl p-5 border border-white/10">Reportes</a>
      <a href="{{ route('admin.settings.index') }}" class="card-glass rounded-xl p-5 border border-white/10">Ajustes</a>
    </div>
  </div>
@endsection
