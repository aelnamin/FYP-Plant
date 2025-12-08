@extends('layouts.main')

@section('title', 'My Cart')

@section('content')

<div class="container my-5">
    <h2 class="mb-4">Shopping Cart</h2>

    ```
    @if(count($cartItems) > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="img-thumbnail me-2" style="width: 70px; height: 70px; object-fit: cover;">
                                    <span>{{ $item->product->name }}</span>
                                </div>
                            </td>
                            <td>${{ number_format($item->product->price, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control me-2" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Cart Summary</h5>
                    <hr>
                    <p>Subtotal: <span class="float-end">${{ number_format($subtotal, 2) }}</span></p>
                    <p>Shipping: <span class="float-end">${{ number_format($shipping, 2) }}</span></p>
                    <hr>
                    <h5>Total: <span class="float-end">${{ number_format($total, 2) }}</span></h5>
                    <a href="{{ route('checkout') }}" class="btn btn-success w-100 mt-3">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <h4>Your cart is empty</h4>
        <a href="{{ route('home') }}" class="btn btn-primary mt-3">Shop Now</a>
    </div>
    @endif
    ```

</div>
@endsection