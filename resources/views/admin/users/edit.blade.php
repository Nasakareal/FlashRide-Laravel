@extends('layouts.app')
@section('title','Editar usuario')
@section('content')
  <h1 class="text-xl font-bold mb-4">Editar usuario</h1>
  <form method="POST" action="{{ route('admin.users.update',$user) }}" class="space-y-4 max-w-lg">
    @csrf @method('PUT')
    <input name="name" value="{{ old('name',$user->name) }}" class="input w-full rounded-lg px-3 py-2.5">
    <input name="email" type="email" value="{{ old('email',$user->email) }}" class="input w-full rounded-lg px-3 py-2.5">
    <div>
      <label class="block text-sm mb-1">Rol</label>
      <select name="role" class="input w-full rounded-lg px-3 py-2.5">
        @foreach(['admin','driver','passenger'] as $r)
          <option value="{{ $r }}" @selected($user->hasRole($r))>{{ $r }}</option>
        @endforeach
      </select>
    </div>
    <button class="btn-neo px-4 py-2 rounded-lg">Actualizar</button>
  </form>
@endsection
