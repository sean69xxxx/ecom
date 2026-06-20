@extends('lab.layout')

@section('content')
<section class="panel">
    <h1>Register</h1>
    <form method="post" action="/register">
        @csrf
        <label for="name">Name</label>
        <input id="name" name="name" value="">

        <label for="email">Email</label>
        <input id="email" name="email" value="">
        @error('email') <div class="notice error" style="margin-top: 4px; padding: 4px;">{{ $message }}</div> @enderror

        <label for="password">Password</label>
        <input id="password" name="password" type="password" value="">
        @error('password') <div class="notice error" style="margin-top: 4px; padding: 4px;">{{ $message }}</div> @enderror

        <button type="submit">Create account</button>
    </form>
</section>
@endsection
