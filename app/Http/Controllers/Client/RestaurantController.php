<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Product;
use App\Models\City;
use App\Models\Gallery;

class RestaurantController extends Controller
{
    ////////////////////////////////
    /////////// Menu Methods ///////
    ////////////////////////////////



    public function AllMenu()
    {
        $clientId = Auth::guard('client')->id();
        $menu = Menu::where('client_id', $clientId)->orderBy('id', 'desc')->get();
        return view('client.backend.menu.all_menu', compact('menu'));
    }

    public function AddMenu()
    {
        return view('client.backend.menu.add_menu');
    }


    public function StoreMenu(Request $request)
{
    $request->validate([
        'menu_name' => 'required|string|max:255',
        'image' => 'required|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/webp|max:4096',
    ]);

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

        $img = $manager->read($image);
        $img->resize(300, 300)->save(public_path('upload/menu/' . $name_gen));

        $save_url = 'upload/menu/' . $name_gen;

        Menu::create([
            'menu_name' => $request->menu_name,
            'client_id' => Auth::guard('client')->id(),
            'image' => $save_url,
        ]);
    }

    return redirect()->route('all.menu')->with([
        'message' => 'Menu Inserted Successfully',
        'alert-type' => 'success'
    ]);
    }

    public function EditMenu($id)
    {
        $menu = Menu::findOrFail($id);
        return view('client.backend.menu.edit_menu', compact('menu'));
    }

    public function UpdateMenu(Request $request)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $menu = Menu::findOrFail($request->id);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($menu->image && file_exists(public_path($menu->image))) {
                unlink(public_path($menu->image));
            }

            // Upload new image
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(300, 300)->save(public_path('upload/menu/' . $name_gen));
            $save_url = 'upload/menu/' . $name_gen;

            $menu->update([
                'menu_name' => $request->menu_name,
                'image' => $save_url,
            ]);
        } else {
            $menu->update([
                'menu_name' => $request->menu_name,
            ]);
        }

        $notification = [
            'message' => 'Menu Updated Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.menu')->with($notification);
    }

    public function DeleteMenu($id)
    {
        $menu = Menu::findOrFail($id);

        // Delete image file
        if ($menu->image && file_exists(public_path($menu->image))) {
            unlink(public_path($menu->image));
        }

        $menu->delete();

        $notification = [
            'message' => 'Menu Deleted Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    ////////////////////////////////
    ///////// Product Methods //////
    ////////////////////////////////

    public function AllProduct()
    {
        $clientId = Auth::guard('client')->id();
        $product = Product::where('client_id', $clientId)->orderBy('id', 'desc')->get();
        return view('client.backend.menu.product.all_product', compact('product'));
    }

    public function AddProduct()
    {
        $clientId = Auth::guard('client')->id();
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::where('client_id', $clientId)->latest()->get();
        return view('client.backend.menu.product.add_product', compact('category', 'city', 'menu'));
    }

    public function StoreProduct(Request $request)
{
    // dd("PRODUCT FORM HIT", $request->all(), $request->file('image'));

    if ($request->city_id === "Select") {
        $request->merge(['city_id' => null]);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        // 'city_id' => 'nullable|exists:cities,id',
        'menu_id' => 'nullable|exists:menus,id',
        'qty' => 'required|integer',
        'size' => 'nullable|string|max:50',
        'price' => 'required|numeric',
        'discount_price' => 'nullable|numeric',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
    ]);


        $pcode = IdGenerator::generate(['table' => 'products', 'field' => 'code', 'length' => 5, 'prefix' => 'PC']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $ext = $image->getClientOriginalExtension() ?: 'webp';
            $name_gen = hexdec(uniqid()) . '.' . $ext;
            $img = $manager->read($image);
            $img->resize(300, 300)->save(public_path('upload/product/' . $name_gen));
            $save_url = 'upload/product/' . $name_gen;

            Product::create([
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ', '-', $request->name)),
                'category_id' => $request->category_id,
                // 'city_id' => $request->city_id,
                'menu_id' => $request->menu_id,
                'code' => $pcode,
                'qty' => $request->qty,
                'size' => $request->size,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'client_id' => Auth::guard('client')->id(),
                'most_populer' => $request->most_populer ?? 0,
                'best_seller' => $request->best_seller ?? 0,
                'status' => 1,
                'created_at' => Carbon::now(),
                'image' => $save_url,
            ]);
        }

        $notification = [
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.product')->with($notification);
    }

    public function EditProduct($id)
    {
        $clientId = Auth::guard('client')->id();
        $category = Category::latest()->get();
        // $city = City::latest()->get();
        $menu = Menu::where('client_id', $clientId)->latest()->get();
        $product = Product::findOrFail($id);
        return view('client.backend.menu.product.edit_product', compact('category', 'menu', 'product'));
    }

    public function UpdateProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            // 'city_id' => 'required|exists:cities,id',
            'menu_id' => 'required|exists:menus,id',
            'qty' => 'required|integer',
            'size' => 'nullable|string|max:50',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($request->id);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            // Upload new image
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(300, 300)->save(public_path('upload/product/' . $name_gen));
            $save_url = 'upload/product/' . $name_gen;

            $product->update([
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ', '-', $request->name)),
                'category_id' => $request->category_id,
                // 'city_id' => $request->city_id,
                'menu_id' => $request->menu_id,
                'qty' => $request->qty,
                'size' => $request->size,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'most_populer' => $request->most_populer ?? 0,
                'best_seller' => $request->best_seller ?? 0,
                'image' => $save_url,
            ]);
        } else {
            $product->update([
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ', '-', $request->name)),
                'category_id' => $request->category_id,
                // 'city_id' => $request->city_id,
                'menu_id' => $request->menu_id,
                'qty' => $request->qty,
                'size' => $request->size,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'most_populer' => $request->most_populer ?? 0,
                'best_seller' => $request->best_seller ?? 0,
            ]);
        }

        $notification = [
            'message' => 'Product Updated Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.product')->with($notification);
    }

    public function DeleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // Delete image file
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        $notification = [
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    public function ChangeStatus(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->status = $request->status;
        $product->save();

        return response()->json(['success' => 'Status Changed Successfully']);
    }

    ////////////////////////////////
    ///////// Gallery Methods //////
    ////////////////////////////////

    public function AllGallery()
    {
        $clientId = Auth::guard('client')->id();
        $gallery = Gallery::where('client_id', $clientId)->latest()->get();
        return view('client.backend.menu.gallery.all_gallery', compact('gallery'));
    }

    public function AddGallery()
    {
        return view('client.backend.menu.gallery.add_gallery');
    }

    public function StoreGallery(Request $request)
{
    $request->validate([
        'gallery_img' => 'required',
        'gallery_img.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
    ]);

    if (!$request->hasFile('gallery_img')) {
        return back()->with('error', 'Please select at least one image');
    }

    foreach ($request->file('gallery_img') as $image) {

        $manager = new ImageManager(new Driver());
        $ext = $image->getClientOriginalExtension() ?: 'webp';
        $name_gen = hexdec(uniqid()) . '.' . $ext;

        $manager->read($image)
            ->resize(800, 800)
            ->save(public_path('upload/gallery/' . $name_gen));

        Gallery::create([
            'client_id' => Auth::guard('client')->id(),
            'gallery_img' => 'upload/gallery/' . $name_gen,
        ]);
    }

    return redirect()->route('all.gallery')->with([
        'message' => 'Gallery Images Inserted Successfully',
        'alert-type' => 'success',
    ]);
}


    public function EditGallery($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('client.backend.menu.gallery.edit_gallery', compact('gallery'));
    }

    public function UpdateGallery(Request $request)
{
    $request->validate([
        'gallery_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
    ]);

    $gallery = Gallery::findOrFail($request->id);

    if ($request->hasFile('gallery_img')) {

        if ($gallery->gallery_img && file_exists(public_path($gallery->gallery_img))) {
            unlink(public_path($gallery->gallery_img));
        }

        $image = $request->file('gallery_img');
        $manager = new ImageManager(new Driver());
        $ext = $image->getClientOriginalExtension() ?: 'webp';
        $name_gen = hexdec(uniqid()) . '.' . $ext;

        $manager->read($image)
            ->resize(800, 800)
            ->save(public_path('upload/gallery/' . $name_gen));

        $gallery->update([
            'gallery_img' => 'upload/gallery/' . $name_gen,
        ]);
    }

    return redirect()->route('all.gallery')->with([
        'message' => 'Gallery Image Updated Successfully',
        'alert-type' => 'success',
    ]);
}


    public function DeleteGallery($id)
    {
        $gallery = Gallery::findOrFail($id);

        // Delete image file
        if ($gallery->gallery_img && file_exists(public_path($gallery->gallery_img))) {
            unlink(public_path($gallery->gallery_img));
        }

        $gallery->delete();

        $notification = [
            'message' => 'Gallery Image Deleted Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }
}
