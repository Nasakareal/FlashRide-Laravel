@extends('layouts.app')
@section('title','Vehículos')

@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold">Vehículos</h1>
    <a href="{{ route('admin.vehicles.create') }}" class="btn-neo px-4 py-2 rounded-lg">Nuevo</a>
  </div>
  <div class="card-glass rounded-xl border border-white/10 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="border-b border-white/10"><th class="p-3 text-left">ID</th><th class="p-3 text-left">Placas</th><th class="p-3 text-left">Marca</th><th class="p-3"></th></tr></thead>
      <tbody>
        @foreach($vehicles as $v)
          <tr class="border-b border-white/10">
            <td class="p-3">{{ $v->id }}</td>
            <td class="p-3">{{ $v->plate ?? '—' }}</td>
            <td class="p-3">{{ $v->brand ?? '—' }}</td>
            <td class="p-3 text-right">
              <a class="underline" href="{{ route('admin.vehicles.edit',$v) }}">Editar</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $vehicles->links() }}</div>
@endsection
