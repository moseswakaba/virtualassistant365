<?php

namespace App\Http\Controllers\Admin;

use App\File;
use App\Image;
use App\Task;
use Illuminate\Http\Request;
use App\User;
use App\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JD\Cloudder\Facades\Cloudder;

class AdminController extends Controller
{
    //
    public function getUser (){
        $user = User::findOrFail(Auth::id());
        $profile = Profile::where('user_id',Auth::id())->first();
        $role = DB::table('role_user')
            ->leftJoin('roles','role_user.role_id','=','roles.id')
            ->where('role_user.user_id',Auth::id())
            ->first();
        $tasks = Task::all()->count();
        $files = File::all()->count();
        $data = [
            'user'=>$user,
            'profile'=>$profile,
            'role'=>$role,
            'tasks'=>$tasks,
            'files'=>$files
        ];
        return $data;
    }

    public function index (){
        $data = $this->getUser();
        return view('admin.index',compact('data'));
    }

    public function profile ($id){
        $profile = Profile::where('user_id',$id)->first();
        $profile->name = User::findOrFail($id)->name;
        $data = $this->getUser();
        return view('admin.profile',compact('data','profile'));
    }

    public function updateProfile (Request $request,$id){
        $this->validate($request,[
            'image'=>'mimes:jpeg,bmp,jpg,png|between:1, 6000'
        ]);
        $profile = Profile::where('user_id',$id)->first();
        $user = User::findOrFail($id);
        //upload image
        if($request->hasFile('image')){
            //save image locally
            $image = $request->file('image');
            $iname = $request->file('image')->getClientOriginalName();

            //save to uploads directory
            $image->move(public_path("uploads/images"), $iname);
            $profile->image = $iname;
        }
        if(!empty($request->input('name'))){
            $user->name = $request->input('name');
        }
        if(!empty($request->input('mobile'))){
            $checkno = Profile::where('mobile',$request->input('mobile'))->first();
            if($checkno->id == $profile->id){
                $profile->mobile = $request->input('mobile');
            }
            else {
                return redirect()->back()->with('warning','Phone number already taken');
            }
        }
        if(!empty($request->input('dob'))){
            $profile->dob = $request->input('dob');
        }
        if(!empty($request->input('gender'))){
            $profile->gender = $request->input('gender');
        }
        if(!empty($request->input('address'))){
            $profile->address = $request->input('address');
        }
        $profile->save();
        $user->save();
        return redirect()->back()->with('message','Profile updated successfully');
    }
}
