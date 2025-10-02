@extends('layouts.app')
@section('title','Usuarios')

@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold">Usuarios</h1>
    <a href="{{ route('admin.users.create') }}" class="btn-neo px-4 py-2 rounded-lg">Nuevo</a>
  </div>

  <div class="card-glass rounded-xl border border-white/10 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="text-white/70">
        <tr class="border-b border-white/10">
          <th class="text-left p-3">ID</th>
          <th class="text-left p-3">Nombre</th>
          <th class="text-left p-3">Email</th>
          <th class="text-left p-3">Roles</th>
          <th class="text-left p-3"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
          <tr class="border-b border-white/10">
            <td class="p-3">{{ $u->id }}</td>
            <td class="p-3">{{ $u->name }}</td>
            <td class="p-3">{{ $u->email }}</td>
            <td class="p-3">{{ $u->getRoleNames()->join(', ') }}</td>
            <td class="p-3 text-right">
              <a href="{{ route('admin.users.show',$u) }}" class="underline">Ver</a>
              <a href="{{ route('admin.users.edit',$u) }}" class="ml-3 underline">Editar</a>
            </td>
          </tr>
        @empty
          <tr><td class="p-4" colspan="5">Sin registros.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $users->links() }}</div>
@endsection
