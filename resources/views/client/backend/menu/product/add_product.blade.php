@extends('client.client_dashboard')
@section('client')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Add Product</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Product</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body p-4">

                        <form id="myForm" action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">

                                <!-- Category -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Category Name</label>
                                        <select name="category_id" class="form-select">
                                            <option>Select</option>
                                            @foreach ($category as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Menu -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Menu Name</label>
                                        <select name="menu_id" class="form-select">
                                            <option selected disabled>Select</option>
                                            @foreach ($menu as $men)
                                                <option value="{{ $men->id }}">{{ $men->menu_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Product Name -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Product Name</label>
                                        <input class="form-control" type="text" name="name">
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Price</label>
                                        <input class="form-control" type="text" name="price">
                                    </div>
                                </div>

                                <!-- Discount Price -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Discount Price</label>
                                        <input class="form-control" type="text" name="discount_price">
                                    </div>
                                </div>

                                <!-- Size -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Size</label>
                                        <input class="form-control" type="text" name="size">
                                    </div>
                                </div>

                                <!-- QTY -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Product QTY</label>
                                        <input class="form-control" type="text" name="qty">
                                    </div>
                                </div>

                                <!-- Veg / Non-Veg -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="veg_nonveg" class="form-label">Veg / Non-Veg</label>
                                        <select name="veg_nonveg" id="veg_nonveg" class="form-select">
                                            <option value="veg">🟢 Veg</option>
                                            <option value="nonveg">🔴 Non-Veg</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Product Image Upload -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Product Image</label>
                                        <input class="form-control" name="image" type="file" id="image">
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div class="col-xl-4 col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Image Preview</label><br>
                                        <img id="showImage" src="{{ url('upload/no_image.jpg') }}" alt="Preview"
                                            class="rounded-circle p-1 bg-primary" width="80">
                                    </div>
                                </div>

                                <!-- Checkboxes -->
                                <div class="col-12">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" name="best_seller" type="checkbox"
                                            id="bestSeller" value="1">
                                        <label class="form-check-label" for="bestSeller">Best Seller</label>
                                    </div>

                                    <div class="form-check mt-2">
                                        <input class="form-check-input" name="most_populer" type="checkbox"
                                            id="mostPopular" value="1">
                                        <label class="form-check-label" for="mostPopular">Most Popular</label>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save Product</button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#image').change(function (e) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#showImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#myForm').validate({
            rules: {
                name: { required: true },
                image: { required: true },
            },
            messages: {
                name: { required: 'Please Enter Product Name' },
                image: { required: 'Please Select an Image' },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>

@endsection
