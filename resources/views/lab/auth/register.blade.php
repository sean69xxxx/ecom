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

        <label for="password">Password</label>
        <input id="password" name="password" type="password" value="">

        <button type="submit">Create account</button>
    </form>
</section>
@endsection
