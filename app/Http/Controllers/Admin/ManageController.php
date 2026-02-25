<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Menu;
use App\Models\Client;
use App\Models\Product;
use App\Models\City;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;
use App\Models\Gallery;
use App\Models\Banner;

class ManageController extends Controller
{
    ////////////////////////////////
    // PRODUCT MANAGEMENT METHODS //
    ////////////////////////////////

    public function AdminAllProduct(){
        $product = Product::orderBy('id','desc')->get();
        return view('admin.backend.category.product.all_product', compact('product'));
    }

    public function AdminAddProduct(){

    $categories = Category::latest()->get();
    $cities = City::latest()->get();
    $menus = Menu::latest()->get();
    $clients = Client::latest()->get();

    return view('admin.backend.category.product.add_product', compact('categories','cities','menus','clients'));
}


    public function AdminStoreProduct(Request $request){
        $pcode = IdGenerator::generate(['table' => 'product','field' => 'code', 'length' => 5, 'prefix' => 'PC']);

        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'city_id' => 'required',
            'menu_id' => 'required',
            'client_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(300,300)->save(public_path('upload/product/'.$name_gen));
            $save_url = 'upload/product/'.$name_gen;

            Product::create([
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ','-',$request->name)),
                'category_id' => $request->category_id,
                'city_id' => $request->city_id,
                'menu_id' => $request->menu_id,
                'code' => $pcode,
                'qty' => $request->qty,
                'size' => $request->size,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'client_id' => $request->client_id,
                'most_populer' => $request->most_populer ?? 0,
                'best_seller' => $request->best_seller ?? 0,
                'status' => 1,
                'created_at' => Carbon::now(),
                'image' => $save_url,
            ]);
        }

        $notification = [
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.all.product')->with($notification);
    }

    public function AdminEditProduct($id){
        $categories = Category::latest()->get();
        $cities = City::latest()->get();
        $menus = Menu::latest()->get();
        $client = Client::latest()->get();
        $product = Product::findOrFail($id);
        return view('admin.backend.product.edit_product', compact('categories','cities','menus','product','client'));
    }

    public function AdminUpdateProduct(Request $request){
        $pro_id = $request->id;
        $product = Product::findOrFail($pro_id);

        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'city_id' => 'required',
            'menu_id' => 'required',
            'client_id' => 'required',
        ]);

        if ($request->file('image')) {
            // Delete old image
            if(file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(300,300)->save(public_path('upload/product/'.$name_gen));
            $save_url = 'upload/product/'.$name_gen;

            $product->update([
                'image' => $save_url,
            ]);
        }

        $product->update([
            'name' => $request->name,
            'slug' => strtolower(str_replace(' ','-',$request->name)),
            'category_id' => $request->category_id,
            'city_id' => $request->city_id,
            'menu_id' => $request->menu_id,
            'client_id' => $request->client_id,
            'qty' => $request->qty,
            'size' => $request->size,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'most_populer' => $request->most_populer ?? 0,
            'best_seller' => $request->best_seller ?? 0,
            'created_at' => Carbon::now(),
        ]);

        $notification = [
            'message' => 'Product Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.all.product')->with($notification);
    }

    public function AdminDeleteProduct($id){
        $product = Product::findOrFail($id);

        if(file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        $notification = [
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    ////////////////////////////////
    // CLIENT MANAGEMENT METHODS //
    ///////////////////////////////

    public function pendingRestaurant()
{
    // Fetch pending restaurants (clients with status = 0)
    $client = Client::where('status', 0)->get();

    // Pass the $clients variable to the view
    return view('admin.backend.category.restaurant.pending_restaurant', compact('client'));
}

    public function ClientChangeStatus(Request $request){
        $client = Client::findOrFail($request->client_id);
        $client->status = $request->status;
        $client->save();
        return response()->json(['success' => 'Status Changed Successfully']);
    }

    public function ApproveRestaurant(){
        $client = Client::where('status',1)->get();
        return view('admin.backend.category.restaurant.approve_restaurant',compact('client'));
    }

    //////////////////////////////
    // BANNER MANAGEMENT METHODS //
    //////////////////////////////

    public function AllBanner(){
        $banners = Banner::latest()->get();
        return view('admin.backend.category.banner.all_banner', compact('banners'));
    }

    public function BannerStore(Request $request){
        $request->validate([
            'url' => 'nullable|url',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create directory if needed
        $dir = public_path('upload/banner');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $image->move($dir, $name_gen);

        Banner::create([
            'url' => $request->url,
            'image' => 'upload/banner/'.$name_gen,
        ]);

        $notification = [
            'message' => 'Banner Added Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.banner')->with($notification);
    }

    public function BannerUpdate(Request $request){
        $banner = Banner::findOrFail($request->banner_id);

        $request->validate([
            'url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if(file_exists(public_path($banner->image))) {
                unlink(public_path($banner->image));
            }

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $image->move(public_path('upload/banner'), $name_gen);

            $banner->update([
                'image' => 'upload/banner/'.$name_gen,
            ]);
        }

        $banner->update([
            'url' => $request->url,
        ]);

        $notification = [
            'message' => 'Banner Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.banner')->with($notification);
    }

    public function DeleteBanner($id){
        $banner = Banner::findOrFail($id);

        if(file_exists(public_path($banner->image))) {
            unlink(public_path($banner->image));
        }

        $banner->delete();

        $notification = [
            'message' => 'Banner Deleted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}
