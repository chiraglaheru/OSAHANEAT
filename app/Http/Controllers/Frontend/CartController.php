<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use App\Models\Coupon;

class CartController extends Controller
{
    public function AddToCart($id){

        if (Session::has('coupon')) {
            Session::forget('coupon');
        }

        $products = Product::find($id);

        $cart = session()->get('cart',[]);
        if (isset($cart[$id])) {
           $cart[$id]['quantity']++;
        } else {
           $priceToShow = isset($products->discount_price) ? $products->discount_price : $products->price;
           $cart[$id] = [
            'id' => $id,
            'name' => $products->name,
            'image' => $products->image,
            'price' => $priceToShow,
            'client_id' => $products->client_id,
            'quantity' => 1
           ];
        }
        session()->put('cart',$cart);

        // return response()->json($cart);
        $notification = array(
            'message' => 'Add to Cart Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
    //End Method

    public function updateCartQuanity(Request $request){
        $cart = session()->get('cart',[]);

        if (isset($cart[$request->id])) {
           $cart[$request->id]['quantity'] = $request->quantity;
           session()->put('cart',$cart);
        }

       return response()->json([
        'message' => 'Quantity Updated',
        'alert-type' => 'success'
       ]);

    }
     //End Method

     public function CartRemove(Request $request){
        $cart = session()->get('cart',[]);

        if (isset($cart[$request->id])) {
           unset($cart[$request->id]);
           session()->put('cart',$cart);
        }
        return response()->json([
        'message' => 'Cart Remove Successfully',
        'alert-type' => 'success'
       ]);
     }
      //End Method

    public function ApplyCoupon(Request $request){

    // FIX: clean input (remove spaces + lowercase)
    $enteredCoupon = strtolower(trim($request->coupon));

    // FIX: search coupon properly
    $coupon = Coupon::whereRaw('LOWER(TRIM(coupon_name)) = ?', [$enteredCoupon])
                    ->whereDate('validity', '>=', Carbon::today())
                    ->first();

    $cart = session()->get('cart',[]);
    $totalAmount = 0;
    $clientIds = [];

    foreach($cart as $car){
        $totalAmount += ($car['price'] * $car['quantity']);
        $pd = Product::find($car['id']);
        $cdid = $pd->client_id;
        array_push($clientIds,$cdid);
    }


    if ($coupon) {

        if (count(array_unique($clientIds)) === 1) {
            $cvendorId = $coupon->client_id;

            if ($cvendorId == $clientIds[0]) {

                Session::put('coupon',[
                    'coupon_name' => $coupon->coupon_name,
                    'discount' => $coupon->discount,
                    'discount_amount' => $totalAmount - ($totalAmount * $coupon->discount/100),
                ]);

                return response()->json([
                    'validity' => true,
                    'success' => 'Coupon Applied Successfully',
                    'couponData' => Session()->get('coupon'),
                ]);

            } else {
                return response()->json(['error' => 'This Coupon Not Valid for this Restaurant']);
            }

        } else {
            return response()->json(['error' => 'This Coupon is only for one restaurant']);
        }
    }

    return response()->json(['error' => 'Invalid Coupon']);
}

     //End Method

     public function CouponRemove(){
        Session::forget('coupon');
        return response()->json(['success' => 'Coupon Remove Successfully']);
     }
     //End Method

    public function ShopCheckout(){
        if (Auth::check()) {
            $cart = session()->get('cart',[]);
            $totalAmount = 0;
            foreach ($cart as $car) {
                $totalAmount += $car['price'];
            }

            if ($totalAmount > 0) {
               return view('frontend.checkout.view_checkout', compact('cart'));
            } else {

                $notification = array(
                    'message' => 'Shopping at list one item',
                    'alert-type' => 'error'
                );
                return redirect()->to('/')->with($notification);
            }

        }else{

            $notification = array(
                'message' => 'Please Login First',
                'alert-type' => 'success'
            );

            return redirect()->route('login')->with($notification);
        }
    }
    //End Method



}
