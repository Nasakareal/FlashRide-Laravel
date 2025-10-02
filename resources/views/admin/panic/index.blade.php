@extends('layouts.app')
@section('title','Incidentes de Pánico')
@section('content')
  <h1 class="text-xl font-bold mb-4">Incidentes de pánico</h1>
  <div class="card-glass rounded-xl border border-white/10 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="border-b border-white/10"><th class="p-3 text-left">ID</th><th class="p-3 text-left">Usuario</th><th class="p-3 text-left">Fecha</th><th class="p-3"></th></tr></thead>
      <tbody>
        @forelse($incidents as $i)
          <tr class="border-b border-white/10">
            <td class="p-3">{{ $i->id }}</td>
            <td class="p-3">{{ optional($i->user)->name }}</td>
            <td class="p-3">{{ $i->created_at }}</td>
            <td class="p-3 text-right"><a class="underline" href="{{ route('admin.panic.show',$i) }}">Ver</a></td>
          </tr>
        @empty
          <tr><td class="p-4" colspan="4">Sin incidentes.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $incidents->links() }}</div>
@endsection
