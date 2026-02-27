<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UserController extends Controller
{
    public function Index(){
        return view('frontend.index');
    }

    public function ProfileStore(Request $request){
        try {
            $id = Auth::user()->id;
            $data = User::find($id);

            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->address = $request->address;

            if ($request->hasFile('photo')) {
                $uploadedFile = Cloudinary::upload($request->file('photo')->getRealPath(), [
                    'folder' => 'foodweb/users',
                    'transformation' => [['width' => 300, 'height' => 300, 'crop' => 'fill']]
                ]);
                $data->photo = $uploadedFile->getSecurePath();
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

    public function UserLogout(){
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('success','Logout Successfully');
    }

    public function ChangePassword(){
        return view('frontend.dashboard.change_password');
    }

    public function UserPasswordUpdate(Request $request){
        $user = Auth::guard('web')->user();
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->old_password,$user->password)) {
            return back()->with([
                'message' => 'Old Password Does not Match!',
                'alert-type' => 'error'
            ]);
        }

        User::whereId($user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with([
            'message' => 'Password Change Successfully',
            'alert-type' => 'success'
        ]);
    }
}
