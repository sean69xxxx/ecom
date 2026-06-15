@extends('lab.layout')

@section('content')
<section class="panel">
    <h1>Transactions</h1>
    <p>Logged in as: {!! session('insecure_user_name') !!}</p>
    <p>Viewing user_id: {!! $selectedUserId !!}</p>

    <form method="post" action="/transactions">
        @csrf
        <input type="hidden" name="user_id" value="{{ session('insecure_user_id') }}">
        <input type="hidden" name="price" value="19.90">

        <label for="product_name">Product</label>
        <input id="product_name" name="product_name" value="Keyboard">

        <label for="quantity">Quantity</label>
        <input id="quantity" name="quantity" value="1">

        <label for="shipping_address">Shipping address</label>
        <textarea id="shipping_address" name="shipping_address">Default address</textarea>

        <label for="note">Note</label>
        <textarea id="note" name="note">Gift wrap please</textarea>

        <button type="submit">Create transaction</button>
    </form>
</section>

<section class="panel">
    <h2>Transaction List</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Product</th>
            <th>Total</th>
            <th>Address</th>
            <th>Note</th>
            <th>Status</th>
            <th>Update</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{!! $transaction->id !!}</td>
                <td>{!! $transaction->user_id !!}</td>
                <td>{!! $transaction->product_name !!}</td>
                <td>RM {!! $transaction->price * $transaction->quantity !!}</td>
                <td>{!! $transaction->shipping_address !!}</td>
                <td>{!! $transaction->note !!}</td>
                <td>{!! $transaction->status !!}</td>
                <td>
                    <form method="post" action="/transactions/{{ $transaction->id }}/status">
                        @csrf
                        <select name="status">
                            <option value="pending">pending</option>
                            <option value="paid">paid</option>
                            <option value="cancelled">cancelled</option>
                        </select>
                        <button type="submit">Save</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>
@endsection
