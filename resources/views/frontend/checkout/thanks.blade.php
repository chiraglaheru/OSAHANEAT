@extends('frontend.dashboard.dashboard')
@section('dashboard')

<section class="section pt-5 pb-5 osahan-not-found-page">
    <div class="container">
       <div class="row">
          <div class="col-md-12 text-center pt-5 pb-5">

             @if(session('token_number'))
    {{-- DINE IN --}}
    <div style="font-size: 80px;">🍽️</div>
    <h1 class="mt-2 mb-2">Order Received!</h1>
    <div class="alert alert-success d-inline-block px-5 py-3 mt-3 mb-3">
       <h4>Your Dine In Token Number</h4>
       <h2 class="font-weight-bold">🎫 #{{ session('token_number') }}</h2>
       <p class="mb-0">Show this to the staff at the counter</p>
    </div>
    {{ session()->forget('token_number') }}  {{-- 👈 ADD THIS --}}
@else
    {{-- HOME DELIVERY --}}
    <div style="font-size: 80px;">🛵</div>
    <h1 class="mt-2 mb-3">Order Received!</h1>
    <div class="alert alert-success d-inline-block px-5 py-3 mb-3">
       <h4>We are on the way! 🚀</h4>
       <p class="mb-0">Your order has been placed and will arrive in <b>30 minutes</b></p>
    </div>
@endif

             <p><b>Check My Orders for more Details</b></p>
             <a class="btn btn-primary btn-lg" href="{{ url('/') }}">GO HOME</a>

          </div>
       </div>
    </div>
</section>

@endsection
