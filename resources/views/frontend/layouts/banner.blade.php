<section class="pt-5 pb-5 homepage-search-block position-relative">
    <div class="banner-overlay"></div>
    <div class="container">
       <div class="row d-flex align-items-center py-lg-4">
          <div class="col-lg-8 mx-auto">
             <div class="homepage-search-title text-center">
                <h1 class="mb-2 display-4 text-shadow text-white font-weight-normal"><span class="font-weight-bold">Your Favorite Meals, Just Clicks Away 🍽️⚡
                </span></h1>
                <h5 class="mb-5 text-shadow text-white-50 font-weight-normal">Lists of top Outlets, cafes, pubs, and bars, based on trends</h5>
             </div>
             <div class="homepage-search-form">
                <form class="form-noborder">
                   <div class="form-row">
                      <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                         <div class="location-dropdown">
                            <i class="icofont-location-arrow"></i>
                            <select class="custom-select form-control-lg">
                               <option> Quick Searches </option>
                               <option> Breakfast </option>
                               <option> Lunch </option>
                               <option> Dinner </option>
                               <option> Cafés </option>
                               <option> Delivery </option>
                            </select>
                         </div>
                      </div>
                      <div class="col-lg-7 col-md-7 col-sm-12 form-group">
    <input id="location-input" type="text" placeholder="Enter your Dine-in location" class="form-control form-control-lg">
    <a class="locate-me" href="#" onclick="getUserLocation(event)">
        <i class="icofont-ui-pointer"></i> Locate Me
    </a>
</div>

                      <div class="col-lg-2 col-md-2 col-sm-12 form-group">
                         <a href="listing.html" class="btn btn-primary btn-block btn-lg btn-gradient">Search</a>
                      </div>
                   </div>
                </form>
             </div>
             <h6 class="mt-4 text-shadow text-white font-weight-normal">E.g. Beverages, Pizzas, Chinese, Bakery, Indian...</h6>
             <div class="owl-carousel owl-carousel-category owl-theme">
    @php
       $products = App\Models\Product::latest()->limit(8)->get();
    @endphp
              @foreach ($products  as $product)
              <div class="item">
                   <div class="osahan-category-item">
                      <a href="#">
                         <img class="img-fluid" src="{{ asset($product->image ) }}" alt="">
                         <h6>{{ Str::limit($product->name, 8)  }}</h6>
                         <p>{{ currency($product->price) }}</p>
                      </a>
                   </div>
                </div>
                @endforeach



             </div>
          </div>

       </div>
    </div>
 </section>

 <script>
function getUserLocation(event) {
    event.preventDefault();

    if (!navigator.geolocation) {
        alert("Geolocation is not supported by your browser.");
        return;
    }

    document.getElementById("location-input").value = "Locating you...";

    navigator.geolocation.getCurrentPosition(function(position) {
        let lat = position.coords.latitude;
        let lng = position.coords.longitude;

        fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=en`)
            .then(function(response) { return response.json(); })
            .then(function(data) {
                let address = [
                    data.locality,
                    data.principalSubdivision,
                    data.countryName
                ].filter(Boolean).join(', ');
                document.getElementById("location-input").value = address || "Location found";
            })
            .catch(function() {
                document.getElementById("location-input").value = lat.toFixed(5) + ', ' + lng.toFixed(5);
            });

    }, function() {
        document.getElementById("location-input").value = "";
        alert("Please allow location access in your browser.");
    }, {
        timeout: 10000,
        maximumAge: 60000
    });
}
</script>
