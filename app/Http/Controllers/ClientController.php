<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\City;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ClientController extends Controller
{
    public function ClientLogin(){
        return view('client.client_login');
   }

   public function ClientRegister(){
        return view('client.client_register');
    }

    public function ClientRegisterSubmit(Request $request){
        $request->validate([
            'name' => ['required','string','max:200'],
            'email' => ['required','string','unique:clients']
        ]);

        Client::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'status' => '0',
        ]);

        $notification = array(
            'message' => 'Client Register Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('client.login')->with($notification);
    }

    public function ClientLoginSubmit(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('client')->attempt($credentials)) {
            Auth::shouldUse('client');
            $request->session()->regenerate();
            return redirect()->route('client.dashboard')->with('success', 'Login Successfully');
        }

        return redirect()->route('client.login')->with('error', 'Invalid Credentials');
    }

    public function ClientDashboard(){
        return view('client.index');
    }

    public function ClientLogout(){
        Auth::guard('client')->logout();
        return redirect()->route('client.login')->with('success','Logout Success');
    }

    public function ClientProfile(){
        $city = City::latest()->get();
        $id = Auth::guard('client')->id();
        $profileData = Client::find($id);
        return view('client.client_profile',compact('profileData','city'));
    }

    public function ClientProfileStore(Request $request){
        try {
            $id = Auth::guard('client')->id();
            $data = Client::find($id);

            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->address = $request->address;
            $data->city_id = $request->city_id;
            $data->shop_info = $request->shop_info;

            if ($request->hasFile('photo')) {
                $uploadedFile = Cloudinary::upload($request->file('photo')->getRealPath(), [
                    'folder' => 'foodweb/clients',
                    'transformation' => [['width' => 300, 'height' => 300, 'crop' => 'fill']]
                ]);
                $data->photo = $uploadedFile->getSecurePath();
            }

            if ($request->hasFile('cover_photo')) {
                $uploadedFile = Cloudinary::upload($request->file('cover_photo')->getRealPath(), [
                    'folder' => 'foodweb/clients',
                    'transformation' => [['width' => 1200, 'height' => 400, 'crop' => 'fill']]
                ]);
                $data->cover_photo = $uploadedFile->getSecurePath();
            }

            $data->save();

            return redirect()->back()->with([
                'message' => 'Profile Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Error: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }

    public function ClientChangePassword(){
        $id = Auth::guard('client')->id();
        $profileData = Client::find($id);
        return view('client.client_change_Password',compact('profileData'));
    }

    public function ClientPasswordUpdate(Request $request){
        $client = Auth::guard('client')->user();
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->old_password,$client->password)) {
            return back()->with([
                'message' => 'Old Password Does not Match!',
                'alert-type' => 'error'
            ]);
        }

        Client::whereId($client->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with([
            'message' => 'Password Change Successfully',
            'alert-type' => 'success'
        ]);
    }
}
