@extends('layouts.app')
@section('title','Crear usuario')
@section('content')
  <h1 class="text-xl font-bold mb-4">Crear usuario</h1>
  <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 max-w-lg">
    @csrf
    <input name="name" class="input w-full rounded-lg px-3 py-2.5" placeholder="Nombre">
    <input name="email" type="email" class="input w-full rounded-lg px-3 py-2.5" placeholder="Email">
    <input name="password" type="password" class="input w-full rounded-lg px-3 py-2.5" placeholder="Contraseña">
    <div>
      <label class="block text-sm mb-1">Rol</label>
      <select name="role" class="input w-full rounded-lg px-3 py-2.5">
        <option value="admin">admin</option>
        <option value="driver">driver</option>
        <option value="passenger">passenger</option>
      </select>
    </div>
    <button class="btn-neo px-4 py-2 rounded-lg">Guardar</button>
  </form>
@endsection
