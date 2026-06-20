@extends('lab.layout')

@section('content')
<section class="panel">
    <h1>Transactions</h1>
    <p>Logged in as: {{ auth()->user()->name }}</p>

    <form method="post" action="/transactions">
        @csrf
        
        <label for="product_name">Product</label>
        <select id="product_name" name="product_name" required>
            <option value="Keyboard">Keyboard (RM 19.90)</option>
            <option value="Wireless Mouse">Wireless Mouse (RM 29.90)</option>
            <option value="USB-C Charger">USB-C Charger (RM 49.90)</option>
        </select>

        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1" required>

        <label for="shipping_address">Shipping address</label>
        <textarea id="shipping_address" name="shipping_address" required>Default address</textarea>

        <label for="note">Note</label>
        <textarea id="note" name="note">Gift wrap please</textarea>

        <button type="submit">Create transaction</button>
    </form>
</section>

<section class="panel">
    <h2>My Transactions</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
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
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->product_name }}</td>
                <td>RM {{ number_format($transaction->price * $transaction->quantity, 2) }}</td>
                <td>{{ $transaction->shipping_address }}</td>
                <td>{{ $transaction->note }}</td>
                <td>{{ ucfirst($transaction->status) }}</td>
                <td>
                    <form method="post" action="/transactions/{{ $transaction->id }}/status">
                        @csrf
                        <select name="status">
                            <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $transaction->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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