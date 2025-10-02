@extends('layouts.app')
@section('title','Conductores')

@section('content')
  <h1 class="text-xl font-bold mb-4">Conductores</h1>
  <div class="card-glass rounded-xl border border-white/10 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="border-b border-white/10">
        <th class="text-left p-3">ID</th>
        <th class="text-left p-3">Nombre</th>
        <th class="text-left p-3">Teléfono</th>
        <th class="text-left p-3">Online</th>
        <th class="text-left p-3"></th>
      </tr></thead>
      <tbody>
        @forelse($drivers as $d)
          <tr class="border-b border-white/10">
            <td class="p-3">{{ $d->id }}</td>
            <td class="p-3">{{ $d->name }}</td>
            <td class="p-3">{{ $d->phone }}</td>
            <td class="p-3">{{ $d->is_online ? 'Sí' : 'No' }}</td>
            <td class="p-3 text-right"><a class="underline" href="{{ route('admin.drivers.show',$d) }}">Ver</a></td>
          </tr>
        @empty
          <tr><td class="p-4" colspan="5">Sin conductores.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $drivers->links() }}</div>
@endsection
