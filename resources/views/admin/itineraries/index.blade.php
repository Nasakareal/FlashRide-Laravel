@extends('layouts.app')
@section('title','Itinerarios')
@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold">Itinerarios</h1>
    <a href="{{ route('admin.itineraries.create') }}" class="btn-neo px-4 py-2 rounded-lg">Nuevo</a>
  </div>
  <div class="card-glass rounded-xl border border-white/10 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="border-b border-white/10"><th class="p-3 text-left">ID</th><th class="p-3 text-left">Nombre</th><th class="p-3 text-left">Estado</th><th class="p-3"></th></tr></thead>
      <tbody>
        @forelse($itineraries as $it)
          <tr class="border-b border-white/10">
            <td class="p-3">{{ $it->id }}</td>
            <td class="p-3">{{ $it->name }}</td>
            <td class="p-3">{{ $it->published ? 'Publicado' : 'Borrador' }}</td>
            <td class="p-3 text-right">
              <a class="underline" href="{{ route('admin.itineraries.edit',$it) }}">Editar</a>
            </td>
          </tr>
        @empty
          <tr><td class="p-4" colspan="4">Sin registros.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $itineraries->links() }}</div>
@endsection
