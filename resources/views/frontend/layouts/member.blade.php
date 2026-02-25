<section class="section pt-5 pb-5 bg-white becomemember-section border-bottom">
    <div class="container">
       <div class="section-header text-center white-text">
          <h2>List Your Brand</h2>
          <p>Join now & enjoy Premium Benefits</p>
          <span class="line"></span>
       </div>
       <div class="row">
          <div class="col-sm-12 text-center">
             <a href="{{ route('client.register') }}?redirect={{ urlencode(request()->fullUrl()) }}" class="btn btn-success btn-lg">
             Create an Account <i class="fa fa-chevron-circle-right"></i>
             </a>
          </div>
       </div>
    </div>
</section>