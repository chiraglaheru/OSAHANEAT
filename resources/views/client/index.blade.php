@extends('client.client_dashboard')
@section('client')

<div class="page-content">
    <div class="container-fluid">

        <!-- Page Title -->
        <div class="row mb-5">
    <div class="col-12 text-center">
        <h1 class="fw-semibold mb-2">Client Dashboard</h1>

        <p class="text-muted fs-5 mb-3">
            Welcome to FoodWeb Client
        </p>

        <!-- small accent line -->
        <div class="mx-auto"
             style="width:70px;height:3px;background:#5156be;border-radius:2px;">
        </div>
    </div>
</div>

        <!-- Food Slider -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">

                        <div id="foodCarousel" class="carousel slide" data-bs-ride="carousel">

                            <div class="carousel-inner">

                                <div class="carousel-item active">
                                    <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092"
                                         class="d-block w-100"
                                         style="height:420px; object-fit:cover;"
                                         alt="Burger">
                                </div>

                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1540189549336-e6e99c3679fe"
                                         class="d-block w-100"
                                         style="height:420px; object-fit:cover;"
                                         alt="Pizza">
                                </div>

                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1565958011703-44f9829ba187"
                                         class="d-block w-100"
                                         style="height:420px; object-fit:cover;"
                                         alt="Dessert">
                                </div>

                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1553621042-f6e147245754"
                                         class="d-block w-100"
                                         style="height:420px; object-fit:cover;"
                                         alt="Indian Food">
                                </div>

                            </div>

                            <!-- Controls -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#foodCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#foodCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
