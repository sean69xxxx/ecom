@extends('lab.layout')

@section('content')
<section class="panel">
    <h1>Login</h1>
    <form method="post" action="/login">
        @csrf
        <label for="email">Email</label>
        <input id="email" name="email" value="{{ old('email') }}">
        @error('email') <div class="notice error" style="margin-top: 4px; padding: 4px;">{{ $message }}</div> @enderror

        <label for="password">Password</label>
        <input id="password" name="password" type="password" >
        @error('password') <div class="notice error" style="margin-top: 4px; padding: 4px;">{{ $message }}</div> @enderror

        <button type="submit">Login</button>
    </form>
</section>
@endsection
