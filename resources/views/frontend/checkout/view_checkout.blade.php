@extends('frontend.dashboard.dashboard')
@section('dashboard')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<style>
   .StripeElement {
   box-sizing: border-box;
   height: 40px;
   padding: 10px 12px;
   border: 1px solid transparent;
   border-radius: 4px;
   background-color: white;
   box-shadow: 0 1px 3px 0 #e6ebf1;
   -webkit-transition: box-shadow 150ms ease;
   transition: box-shadow 150ms ease;
   }
   .StripeElement--focus {
   box-shadow: 0 1px 3px 0 #cfd7df;
   }
   .StripeElement--invalid {
   border-color: #fa755a;
   }
   .StripeElement--webkit-autofill {
   background-color: #fefde5 !important;}
</style>

<script>
function selectAddress(el){
    document.querySelectorAll('.addresses-item').forEach(c=>{
        c.classList.remove('border-success');
        c.querySelector('.btn').classList.remove('btn-success');
        c.querySelector('.btn').classList.add('btn-secondary');
    });

    el.classList.add('border-success');
    el.querySelector('.btn').classList.remove('btn-secondary');
    el.querySelector('.btn').classList.add('btn-success');

    // SET DELIVERY TYPE IN ALL FORMS
    var type = el.innerText.includes("Dine In") ? "dinein" : "home";
    document.getElementById('delivery_type').value = type;
    document.getElementById('delivery_type2').value = type;
}
</script>

<section class="offer-dedicated-body mt-4 mb-4 pt-2 pb-2">
<div class="container">

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Please complete your profile before ordering!</strong>
    <ul class="mb-0 mt-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
@endif

<div class="row">
<div class="col-md-8">
<div class="offer-dedicated-body-left">

@php
$id = Auth::user()->id;
$profileData = App\Models\User::find($id);
@endphp

<div class="pt-2"></div>
<div class="bg-white rounded shadow-sm p-4 mb-4">
<h4 class="mb-1">Choose a delivery address</h4>
<h6 class="mb-3 text-black-50">Multiple addresses in this location</h6>

<div class="row">
<div class="col-md-6">
<div class="bg-white card addresses-item mb-4 border border-success"
     onclick="selectAddress(this)">
<div class="gold-members p-4">
<div class="media">
<div class="mr-3"><i class="icofont-ui-home icofont-3x"></i></div>
<div class="media-body">
<h6 class="mb-1 text-black">Home</h6>
<p class="text-black">{{ $profileData->address }}</p>
<p class="mb-0 text-black font-weight-bold">
<a class="btn btn-sm btn-success mr-2" href="#"> DELIVER HERE</a>
<span>30MIN</span>
</p>
</div>
</div>
</div>
</div>
</div>

<div class="col-md-6">
<div class="bg-white card addresses-item mb-4"
     onclick="selectAddress(this)">
<div class="gold-members p-4">
<div class="media">
<div class="mr-3"><i class="icofont-fast-food icofont-4x"></i></div>
<div class="media-body">
<h6 class="mb-1 text-secondary">Dine In</h6>
<p>Current Store</p>
<p class="mb-0 text-black font-weight-bold">
<a class="btn btn-sm btn-secondary mr-2" href="#"> DELIVER HERE</a>
<span>15MIN-20MIN</span>
</p>
</div>
</div>
</div>
</div>
</div>
</div>

</div>
<div class="bg-white rounded shadow-sm p-4 mb-4">
    <h4 class="mb-1">📱 Get WhatsApp Order Updates</h4>
    <p class="text-muted mb-2">Click the button below to activate WhatsApp notifications before placing your order.</p>
    <a href="https://wa.me/14155238886?text=join%20waste-fireplace" target="_blank" class="btn btn-success">
        <i class="icofont-whatsapp"></i> Activate WhatsApp Notifications
    </a>
</div>
                <div class="pt-2"></div>
    <div class="bg-white rounded shadow-sm p-4 osahan-payment">
        <h4 class="mb-1">Choose payment method</h4>
        <h6 class="mb-3 text-black-50">Credit/Debit Cards</h6>
        <div class="row">
            <div class="col-sm-4 pr-0">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-cash-tab" data-toggle="pill" href="#v-pills-cash" role="tab" aria-controls="v-pills-cash" aria-selected="false"><i class="icofont-money"></i> Pay on Delivery</a>
        <a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="icofont-credit-card"></i> Credit/Debit Cards</a>
                </div>
            </div>
            <div class="col-sm-8 pl-0">
                <div class="tab-content h-100" id="v-pills-tabContent">

<div class="tab-pane fade show active" id="v-pills-cash" role="tabpanel" aria-labelledby="v-pills-cash-tab">
    <h6 class="mb-3 mt-0">Cash</h6>
    <p>Please keep exact change handy to help us serve you better</p>
    <hr>
    <form action="{{ route('cash_order') }}" method="post">
        @csrf
        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
        <input type="hidden" name="phone" value="{{ Auth::user()->phone }}">
        <input type="hidden" name="address" value="{{ Auth::user()->address }}">
        <input type="hidden" name="delivery_type" id="delivery_type" value="home">
        <button type="submit" class="btn btn-success btn-block btn-lg">PAY
        <i class="icofont-long-arrow-right"></i></button>
    </form>
</div>

<div class="tab-pane fade" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
    <h6 class="mb-3 mt-0">Add new card</h6>
    <p>WE ACCEPT <span class="osahan-card">
        <i class="icofont-visa-alt"></i> <i class="icofont-mastercard-alt"></i> <i class="icofont-american-express-alt"></i> <i class="icofont-payoneer-alt"></i> <i class="icofont-apple-pay-alt"></i> <i class="icofont-bank-transfer-alt"></i> <i class="icofont-discover-alt"></i> <i class="icofont-jcb-alt"></i>
        </span>
    </p>
    <form action="{{ route('cash_order') }}" method="post" id="payment-form">
      @csrf
     <label for="card-element"></label>
      <input type="hidden" name="name" value="{{ Auth::user()->name }}">
      <input type="hidden" name="email" value="{{ Auth::user()->email }}">
      <input type="hidden" name="phone" value="{{ Auth::user()->phone }}">
      <input type="hidden" name="address" value="{{ Auth::user()->address }}">
      <input type="hidden" name="delivery_type" id="delivery_type3" value="home">
      <div id="card-element"></div>
      <div id="card-errors" role="alert"></div>
      <br>
      <button type="submit" class="btn btn-success btn-block btn-lg">PAY
      <i class="icofont-long-arrow-right"></i></button>
  </form>
</div>

                </div>
            </div>
        </div>
    </div>
             </div>
          </div>

          @php
          $id = Auth::user()->id;
          $profileData = App\Models\User::find($id);
         @endphp

          <div class="col-md-4">
             <div class="generator-bg rounded shadow-sm mb-4 p-4 osahan-cart-item">
                <div class="d-flex mb-4 osahan-cart-item-profile">
                   <img class="img-fluid mr-3 rounded-pill" alt="osahan" src="{{ (!empty($profileData->photo)) ? url('upload/user_images/'.$profileData->photo) : url('upload/no_image.jpg') }}">
                   <div class="d-flex flex-column">
                      <h6 class="mb-1 text-white">{{ $profileData->name }}</h6>
                      <p class="mb-0 text-white"><i class="icofont-location-pin"></i> {{ $profileData->address }}</p>
                   </div>
                </div>
   <p class="mb-4 text-white">{{ count((array) session('cart')) }} ITEMS</p>
    <div class="bg-white rounded shadow-sm mb-2">

        @php $total = 0 @endphp
   @if (session('cart'))
      @foreach (session('cart') as $id => $details)
      @php
         $total += $details['price'] * $details['quantity']
      @endphp
   <div class="gold-members p-2 border-bottom">
         <p class="text-gray mb-0 float-right ml-2">{{ currency( $details['price'] * $details['quantity'] )}}</p>
         <span class="count-number float-right">
        <button class="btn btn-outline-secondary btn-sm left dec" data-id="{{ $id }}"> <i class="icofont-minus"></i> </button>
         <input class="count-number-input" type="text" value="{{ $details['quantity'] }}" readonly="">
         <button class="btn btn-outline-secondary btn-sm right inc" data-id="{{ $id }}"> <i class="icofont-plus"></i> </button>
         <button class="btn btn-outline-danger btn-sm right remove" data-id="{{ $id }}"> <i class="icofont-trash"></i> </button>
         </span>
         <div class="media">
            <div class="mr-2"><img src="{{ asset($details['image']) }}" width="25px"></div>
            <div class="media-body">
               <p class="mt-1 mb-0 text-black">{{ $details['name'] }}</p>
            </div>
         </div>
      </div>
      @endforeach
      @endif
    </div>

       @if (Session::has('coupon'))
   <div class="mb-2 bg-white rounded p-2 clearfix">
      <p class="mb-1">Item Total <span class="float-right text-dark">{{ count((array) session('cart')) }}</span></p>
      <p class="mb-1">Coupon Name <span class="float-right text-dark">{{ (session()->get('coupon')['coupon_name']) }} ( {{ (session()->get('coupon')['discount']) }} %) </span>
      <a type="submit" onclick="couponRemove()"><i class="icofont-ui-delete float-right" style="color: red;"></i></a>
      </p>
      <p class="mb-1 text-success">Total Discount
         <span class="float-right text-success">
            @if (Session::has('coupon'))
               {{ currency( $total - Session()->get('coupon')['discount_amount'] )}}
            @else
            {{ currency( $total) }}
            @endif
         </span>
      </p>
      <hr />
      <h6 class="font-weight-bold mb-0">TO PAY <span class="float-right">
      @if (Session::has('coupon'))
      {{ currency( Session()->get('coupon')['discount_amount'] )}}
      @else
      {{ currency( $total )}}
      @endif</span></h6>
   </div>
   @else
     <div class="mb-2 bg-white rounded p-2 clearfix">
      <div class="input-group input-group-sm mb-2">
         <input type="text" class="form-control" placeholder="Enter promo code" id="coupon_name">
         <div class="input-group-append">
            <button class="btn btn-primary" type="submit" id="button-addon2" onclick="ApplyCoupon()"><i class="icofont-sale-discount"></i> APPLY</button>
         </div>
      </div>
   </div>
   @endif

       <form action="{{ route('cash_order') }}" method="POST">
    @csrf
    <input type="hidden" name="name" value="{{ Auth::user()->name }}">
    <input type="hidden" name="email" value="{{ Auth::user()->email }}">
    <input type="hidden" name="phone" value="{{ Auth::user()->phone }}">
    <input type="hidden" name="address" value="{{ Auth::user()->address }}">
    <input type="hidden" name="delivery_type" id="delivery_type2" value="home">
    <button type="submit" class="btn btn-success btn-block btn-lg">
        PAY
        @if (Session::has('coupon'))
            {{ currency( Session()->get('coupon')['discount_amount'])}}
        @else
            {{ currency( $total) }}
        @endif
        <i class="icofont-long-arrow-right"></i>
    </button>
</form>

          </div>
       </div>
    </div>
 </section>

 <!-- /////////////////////////----------Start JavaScript  ------- ///////////////////////////// -->
<script type="text/javascript">
   var stripe = Stripe('pk_test_51Oml5cGAwoXiNtjJgPPyQngDj9WTjawya4zCsqTn3LPFhl4VvLZZJIh9fW9wqVweFYC5f0YEb9zjUqRpXbkEKT7T00eU1xQvjp');
   var elements = stripe.elements();
   var style = {
   base: {
   color: '#32325d',
   fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
   fontSmoothing: 'antialiased',
   fontSize: '16px',
   '::placeholder': {
     color: '#aab7c4'
   }
   },
   invalid: {
   color: '#fa755a',
   iconColor: '#fa755a'
   }
   };
   var card = elements.create('card', {style: style});
   card.mount('#card-element');
   card.on('change', function(event) {
   var displayError = document.getElementById('card-errors');
   if (event.error) {
   displayError.textContent = event.error.message;
   } else {
   displayError.textContent = '';
   }
   });
   var form = document.getElementById('payment-form');
   form.addEventListener('submit', function(event) {
   event.preventDefault();
   stripe.createToken(card).then(function(result) {
   if (result.error) {
     var errorElement = document.getElementById('card-errors');
     errorElement.textContent = result.error.message;
   } else {
     stripeTokenHandler(result.token);
   }
   });
   });
   function stripeTokenHandler(token) {
   var form = document.getElementById('payment-form');
   var hiddenInput = document.createElement('input');
   hiddenInput.setAttribute('type', 'hidden');
   hiddenInput.setAttribute('name', 'stripeToken');
   hiddenInput.setAttribute('value', token.id);
   form.appendChild(hiddenInput);
   form.submit();
   }

   // Also sync delivery_type3 (credit card form) when address is selected
   // Also sync delivery_type3 (credit card form) when address is selected
var origSelectAddress = selectAddress;
function selectAddress(el){
    document.querySelectorAll('.addresses-item').forEach(c=>{
        c.classList.remove('border-success');
        c.querySelector('.btn').classList.remove('btn-success');
        c.querySelector('.btn').classList.add('btn-secondary');
    });
    el.classList.add('border-success');
    el.querySelector('.btn').classList.remove('btn-secondary');
    el.querySelector('.btn').classList.add('btn-success');
    var type = el.innerText.includes("Dine In") ? "dinein" : "home";
    document.getElementById('delivery_type').value = type;
    document.getElementById('delivery_type2').value = type;
    document.getElementById('delivery_type3').value = type;
}
</script>
<!-- /////////////////////////----------End JavaScript ------- ///////////////////////////// -->

<script>
function ApplyCoupon() {
    var coupon = $("#coupon_name").val();
    if (!coupon) {
        Swal.fire({ icon: 'error', title: 'Please enter a coupon code' });
        return;
    }
    $.ajax({
        type: "POST",
        url: "/apply-coupon",
        data: { coupon: coupon, _token: "{{ csrf_token() }}" },
        success: function(response) {
            if (response.error) {
                Swal.fire({ icon: 'error', title: response.error });
            }
            if (response.success) {
                Swal.fire({ icon: 'success', title: response.success }).then(() => { location.reload(); });
            }
        }
    });
}
</script>

 <script>
    $(document).ready(function() {
       const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true,
          didOpen: (toast) => {
             toast.addEventListener('mouseenter', Swal.stopTimer);
             toast.addEventListener('mouseleave', Swal.resumeTimer);
          }
       });

       $('.inc').on('click', function() {
          var id = $(this).data('id');
          var input = $(this).closest('span').find('input');
          var newQuantity = parseInt(input.val()) + 1;
          updateQuantity(id, newQuantity);
       });

       $('.dec').on('click', function() {
          var id = $(this).data('id');
          var input = $(this).closest('span').find('input');
          var newQuantity = parseInt(input.val()) - 1;
          if (newQuantity >= 1) { updateQuantity(id, newQuantity); }
       });

       $('.remove').on('click', function() {
          var id = $(this).data('id');
          removeFromCart(id);
       });

       function updateQuantity(id, quantity){
          $.ajax({
             url: '{{ route("cart.updateQuantity") }}',
             method: 'POST',
             data: { _token: '{{ csrf_token() }}', id: id, quantity: quantity },
             success: function(response){
                Toast.fire({ icon: 'success', title: 'Quantity Updated' }).then(() => { location.reload(); });
             }
          })
       }

       function removeFromCart(id){
          $.ajax({
             url: '{{ route("cart.remove") }}',
             method: 'POST',
             data: { _token: '{{ csrf_token() }}', id: id },
             success: function(response){
                Toast.fire({ icon: 'success', title: 'Cart Remove Successfully' }).then(() => { location.reload(); });
             }
          });
       }
    })
  </script>

@endsection
