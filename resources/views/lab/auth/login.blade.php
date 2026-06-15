@extends('lab.layout')

@section('content')
<section class="panel">
    <h1>Login</h1>
    <form method="post" action="/login">
        @csrf
        <label for="email">Email</label>
        <input id="email" name="email" value="alice@example.com">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" value="password123">

        <button type="submit">Login</button>
    </form>
</section>
@endsection
