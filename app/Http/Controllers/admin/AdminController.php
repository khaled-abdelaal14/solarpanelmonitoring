<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(){
        return view('admin.dashboard');
    }

    public function admins(){
        $admins=Admin::where('type','sub_admin')->get();
       
        $adminModulecount=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
        ->id,'module'=>'admins'])->count();
        if(Auth::guard('admin')->user()->type=="admin"){
            $adminsModule['view_access']=1;
            $adminsModule['edit_access']=1;
            $adminsModule['full_access']=1;
        }elseif($adminModulecount==0){
            $message="This Feature Is Restricted For You!";
            return redirect('admin/dashboard')->with('error_message',$message);
        }else{
            $adminsModule=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
            ->id,'module'=>'admins'])->first();
        }
        return view('admin.admins.index',compact('admins','adminsModule'));
    }

    public function updatedetails(Request $request){
        if($request->isMethod('post')){
            
            $rules=[
                'email' => 'required|email|max:255|unique:admins,email,'.Auth::guard('admin')->user()->id,
                'name'=> 'required|max:200',
                'phone'=> 'required|numeric|digits:11|unique:admins,phone,'.Auth::guard('admin')->user()->id,
                'image'=> 'mimes:jpeg,jpg,png,gif|max:1000',
                
            ];
            $custommessage=[
                'email.required'=>'Email is required',
                'email.email'=>'Email valid!',
                'name.required'=>'Name is required',
                'name.ragex'=>'Name valid!',
                'phone.required'=>'mobile is required',
                'phone.numeric'=>' Valid mobile is required',
                'phone.digits'=>'mobile is required 11 Numbers only',
                'phone.unique'=>'mobile is exists',
                
           



            ];
            $this->validate($request,$rules,$custommessage);
            if($request->hasFile('image')){
                $destination='/admin/photos';
                $image=$request->file('image');
                $image_name=$image->getClientOriginalName();

                
                $path=$request->file('image')->storeAs($destination,$image_name,'public');
                
               
            }  else{
                $path = $request->input('currentadminimage');
            }                     
            Admin::where('id',Auth::guard('admin')->user()->id)->update([
                'name'=>$request->name,
                'email'=>$request->email,
                'phone'=>$request->phone,
                'city'=>$request->city,
                'image'=>$path,

            ]);
            return redirect()->back()->with('success_message','informaion has been updated successfully');
        }
        return view('admin.profile.updatedetails');
    }

    public function updatepassword(Request $request){
        if($request->isMethod('post')){
            if(Hash::check($request->currentpassword,Auth::guard('admin')->user()->password)){
                if($request->newpassword==$request->confirmpassword){
                    Admin::where('email',Auth::guard('admin')->user()->email)->update([
                        'password'=>bcrypt($request->newpassword)
                    ]);
                    return redirect()->back()->with('success_message',' Password has been updated successfully');


                }else{
                    return redirect()->back()->with('erorr_message','New Password Not Match Confirm Password ');

                }

            }else{
                return redirect()->back()->with('erorr_message','current password is incorrect!');
            }
        }

        return view('admin.profile.updatepassword');
    }

    public function checkcurrentpassword(Request $request){
        $data=$request->all();
        if(Hash::check($data['currentpassword'],Auth::guard('admin')->user()->password)){
            return "true";
        }else{
            return "false";
        }
    }

    public function addedit(Request $request,$id=null){


        $adminModulecount=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
        ->id,'module'=>'admins'])->count();
        if(Auth::guard('admin')->user()->type=="admin"){
            $adminsModule['view_access']=1;
            $adminsModule['edit_access']=1;
            $adminsModule['full_access']=1;
        }elseif($adminModulecount==0 || AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
        ->id,'module'=>'admins','full_access'=>0])->count()==1){
            $message="This Feature Is Restricted For You!";
            return redirect('admin/dashboard')->with('error_message',$message);
        }else{
            $adminsModule=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
            ->id,'module'=>'admins'])->first();
        }


        if($id==null){
            $title='Add admin';
            $subadmindata=new Admin;
            $message='Admin Added Successfully';
        }else{
            $title='Edit admin';
            $subadmindata=Admin::find($id);
            $message='Admin Updated Successfully'; 
        }
        if($request->isMethod('post')){
            if($id==""){
                $rules=[

                    'email' => 'required|email|max:255|unique:admins,email,'.$id,
                    'name'=>'required',
                    'city' => 'required|string|max:100',
                    'phone'=>'required|numeric|digits:11|unique:admins,phone,'.$id,
                    'image'=> 'mimes:jpeg,jpg,png,gif|max:1000',
                ];
            }
            else{
                $rules=[
                    'name'=>'required',
                    'city' => 'required|string|max:100',
                    'phone'=> 'required|numeric|digits:11|unique:admins,phone,'.$id,
                    'image'=> 'mimes:jpeg,jpg,png,gif|max:1000',
                ];
            }
            
            
           
            $custommessage=[
                'email.required'=>'Email is required',
                'email.email'=>'Email valid!',
                'email.unique'=>'Email exists',
                'name.required'=>'name is required',
                'phone.required'=>'mobile is required',
                'mobile.numeric'=>' valid mobile is required',
                'image.image'=>'Valid Image is required',
                'phone.digits'=>'mobile is required 11 Numbers only',
                'phone.unique'=>'mobile is exists',
            ];
            $this->validate($request,$rules,$custommessage);

            if($request->hasFile('image')){
                $destination='/admin/photos';
                $image=$request->file('image');
                $image_name=$image->getClientOriginalName();

                
                $path=$request->file('image')->storeAs($destination,$image_name,'public');
                
               
            }else if(!empty($request->input('currentadminimage'))){
                $path = $request->input('currentadminimage');
            }else{
                $path = "";
            } 
            $subadmindata->name=$request->name;
            $subadmindata->city=$request->city;
            $subadmindata->phone=$request->phone;
            $subadmindata->image=$path;
            
            if($id==""){
                $subadmindata->email=$request->email;
                $subadmindata->type= "sub_admin";
                $subadmindata->password=bcrypt('password');
            }
            if($request->password != null){
                $subadmindata->password=bcrypt($request->password);
            }
            $subadmindata->save();
            return redirect('admin/admins')->with('success_message',$message);
        }

        return view('admin.admins.add-edit',compact('title','subadmindata'));
    }

    public function deleteadmin($id){
        $this->deleteimage($id);
        Admin::destroy($id);
        return redirect('admin/admins')->with('success_message','Admin Deleted Sussessfully');
    }

    public function deleteimage($id){
        $adminimage=Admin::select('image')->where('id',$id)->first();
        $jsonObject = json_decode($adminimage, true);
        $AdminImage = $jsonObject['image'];
        $fullPath = 'public/' . $AdminImage; 
         
        if(Storage::exists($fullPath)){
            
            Storage::delete($fullPath);
            
        }
        Admin::where('id',$id)->update(['image'=>""]);
        return redirect()->back()->with('success_message','admin Image deleted successfully');

    }

    public function updaterole($id , Request $request){
        $subadmindetails=Admin::where('id',$id)->first();
        $title="update ".$subadmindetails['name']." Roles and Permession";
        if($request->isMethod('post')){
            
            AdminRole::where('admin_id',$id )->delete();
            foreach ($request->except(['_token', 'subadmin_id']) as $module => $permissions) {
                // dd($request);
                // Initialize permissions
                $view = isset($permissions['view']) ? $permissions['view'] : 0;
                $edit = isset($permissions['edit']) ? $permissions['edit'] : 0;
                $full = isset($permissions['full']) ? $permissions['full'] : 0;
        
                // Save permissions for each module
                $role = new AdminRole;
                $role->admin_id = $id;
                $role->module = $module;
                $role->view_access = $view;
                $role->edit_access = $edit;
                $role->full_access = $full;
                $role->save();
            }
            $message="Subadmin Roles Updated Successfully";
            return redirect()->back()->with('success_message',$message);
        }
        $subadminroles = AdminRole::where('admin_id', $id)->get();
        
        return view('admin.admins.update-role',compact('id','title','subadminroles'));
    }

    public function addedituser(Request $request,$id=null){

        $userModulecount=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
        ->id,'module'=>'users'])->count();
        if(Auth::guard('admin')->user()->type=="admin"){
            $usersModule['view_access']=1;
            $usersModule['edit_access']=1;
            $usersModule['full_access']=1;
        }elseif($userModulecount==0 || AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
        ->id,'module'=>'users','full_access'=>0])->count()==1){
            $message="This Feature Is Restricted For You!";
            return redirect('admin/dashboard')->with('error_message',$message);
        }else{
            $usersModule=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
            ->id,'module'=>'users'])->first();
        }

        if($id==null){
            $title='Add user';
            $subadmindata=new User;
            $message='User Added Successfully';
        }else{
            $title='Edit User';
            $subadmindata=User::find($id);
            $message='User Updated Successfully'; 
        }
        if($request->isMethod('post')){
            if($id==""){
                $subadminemail=User::where('email',$request->email)->count();
                if($subadminemail > 0){
                    return redirect()->back()->with('error_message','user email exists!');

                }
            }
            $rules=[
                'email' => 'required|email|max:255|unique:admins,email,'.$id,
                'name'=>'required',
                'city' => 'required|string|max:100',
                'phone'=> 'required|numeric|digits:11|unique:users,phone,'.$id,
                
           
            ];
            $custommessage=[
                'email.required'=>'Email is required',
                'email.email'=>'Email valid!',
                'email.unique'=>'Email exists',
                'name.required'=>'name is required',
                'phone.required'=>'mobile is required',
                'phone.numeric'=>' valid mobile is required',
                'phone.digits'=>'mobile is required 11 Numbers only',
                'phone.unique'=>'mobile is exists',
                'mobile.numeric'=>' valid mobile is required',
              
            ];
            $this->validate($request,$rules,$custommessage);

            
            $subadmindata->name=$request->name;
            $subadmindata->city=$request->city;
            $subadmindata->phone=$request->phone;
            $subadmindata->admin_id=Auth::guard('admin')->user()->id;
          
            
            if($id==""){
                $subadmindata->email=$request->email;
                
                $subadmindata->password=bcrypt('password');
            }
            if($request->password != null){
                $subadmindata->password=bcrypt($request->password);
            }
            $subadmindata->save();
            return redirect('admin/users')->with('success_message',$message);
        }

        return view('admin.admins.add-edit-user',compact('title','subadmindata'));
    }

    public function showusers(){
        $users=User::get();
        $userModulecount=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
        ->id,'module'=>'users'])->count();
        if(Auth::guard('admin')->user()->type=="admin"){
            $usersModule['view_access']=1;
            $usersModule['edit_access']=1;
            $usersModule['full_access']=1;
        }elseif($userModulecount==0){
            $message="This Feature Is Restricted For You!";
            return redirect('admin/dashboard')->with('error_message',$message);
        }else{
            $usersModule=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
            ->id,'module'=>'users'])->first();
        }
        return view('admin.admins.showusers',compact('users','usersModule'));
    }



    public function deleteuser($id){
      
        User::destroy($id);
        return redirect('admin/users')->with('success_message','User Deleted Sussessfully');
    }


    public function showuserdetails($id){
        $user=User::find($id);
        return view('admin.admins.showuserdetails',compact('user'));
    }

    public function addeditdevice(Request $request,$id=null){

        $deviceModulecount=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
        ->id,'module'=>'devices'])->count();
        if(Auth::guard('admin')->user()->type=="admin"){
            $devicesModule['view_access']=1;
            $devicesModule['edit_access']=1;
            $devicesModule['full_access']=1;
        }elseif($deviceModulecount==0 || AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
        ->id,'module'=>'devices','full_access'=>0])->count()==1){
            $message="This Feature Is Restricted For You!";
            return redirect('admin/dashboard')->with('error_message',$message);
        }else{
            $devicesModule=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
            ->id,'module'=>'devices'])->first();
        }
       
        if($id==null){
            $title='Add device';
            $device=new Device;
            $message='Device Added Successfully';
            $users=User::get();
        }else{
            $title='Edit Device';
            $device=Device::find($id);
            $message='Device Updated Successfully'; 
            $users=User::get();
        }
        if($request->isMethod('post')){
           
            if($id==""){
                $serial_number=Device::where('serial_number',$request->serial_number )->count();
                if($serial_number > 0){
                    return redirect()->back()->with('error_message','Serial_Number exists!');

                }
            }
            $rules=[
                'serial_number'=>'required|unique:devices,serial_number,'.$id,
                'user_id'=>'required|exists:users,id|unique:devices,user_id,'.$id,
           
            ];
            $custommessage=[
                'serial_number.required'=>'serial_number is required',
                'serial_number.unique'=>'serial_number is exists',
                'user_id.required'=>'user_id is required',
               'user_id.exsis'=>'user_id is not exists',
               'user_id.unique'=>'this user has a device',
              
            ];
            $this->validate($request,$rules,$custommessage);

            
            $device->serial_number=$request->serial_number;
            $device->status='off';
            $device->user_id=$request->user_id;
         
            $device->save();
            
            return redirect('admin/devices')->with('success_message',$message);
        }

        return view('admin.device.add-edit',compact('title','device','users'));
    }

    public function showdevices(){
        $devices=Device::get();
        $deviceModulecount=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
        ->id,'module'=>'devices'])->count();
        if(Auth::guard('admin')->user()->type=="admin"){
            $devicesModule['view_access']=1;
            $devicesModule['edit_access']=1;
            $devicesModule['full_access']=1;
        }elseif($deviceModulecount==0){
            $message="This Feature Is Restricted For You!";
            return redirect('admin/dashboard')->with('error_message',$message);
        }else{
            $devicesModule=AdminRole::where(['admin_id'=> Auth::guard('admin')->user()
            ->id,'module'=>'devices'])->first();
        }
        return view('admin.device.index',compact('devices','devicesModule'));
    }

    public function deletedevice($id){
      
        Device::destroy($id);
        return redirect('admin/devices')->with('success_message','Device Deleted Sussessfully');
    }

}
