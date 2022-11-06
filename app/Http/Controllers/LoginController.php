<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Admin;

use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
   
    function login(){

        return  view('login.login');
      }
  
      function checklogin(Request $req){
  
          $data = Admin::where('user_name', $req->user_name)->first();
          
          if($req->user_name == $data['user_name'] && $req->user_password == $data['user_password']  ){
  
            
              $req->session()->put('user_name',$data['user_name']);
              $req->session()->put('user_password',$data['user_password']);
              $req->session()->put('user_type',$data['user_type']);
              return  redirect('index');
              
          }else{
              return view('login.login');
          }
      }
  
      function logout(){
  
          session()->pull('user_name');
          session()->pull('user_password');
          session()->pull('user_type');
          return  redirect(url('/'));
      }
  
    //   function addUserDetails(Request $req){
  
    //       $rules = [
    //           'identity' => 'required',
    //           'lastname'=> 'required',
    //           'email'=> 'required',
    //           'firstname'=> 'required',
    //       ];
    //       $validator = Validator::make($req->all(), $rules);
    //       if ($validator->fails()) {
    //           $messages = $validator->errors()->all();
    //           $msg = $messages[0];
    //           return response()->json(['status' => 401, 'message' => $msg]);
    //       }
  
    //       $data = Users::where('identity',$req->identity)->first();
         
    //       if($data == null){
  
              
          
  
    //       $user = new Users;
  
    //       if($req->has('image')){
  
    //           $path = $req->file('image')->store('uploads');
  
    //           $user->image = $path;
    //       }
  
    //       // $user->identity = $req->identity;
    //       $user->firstname = $req->firstname; 
    //       $user->lastname = $req->lastname;
    //       $user->email = $req->email;
    //       $user->save();
    //       $data =  Users::latest()->first();
    //       return json_encode(['status'=>true ,'message'=>'User Add Success','data'=> $data ]);
          
    //   }else{
    //       return json_encode(['status'=>true ,'message'=>'User All Ready Exists','data'=> $data]);
    //   }
   
  
  
    //   }
}
