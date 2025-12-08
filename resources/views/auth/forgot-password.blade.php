@extends('layouts.main')

@section('title', 'Forgot Password')

@section('content')
<section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm p-4 rounded-4">
                <h4 class="text-center mb-3">Reset Password</h4>

                <p class="text-center small text-muted">
                    Enter your email and we will send you a reset link.
                </p>

                <form action="#" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control rounded-3" required>
                    </div>

                    <button class="btn btn-success w-100 rounded-pill">
                        Send Reset Link
                    </button>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection