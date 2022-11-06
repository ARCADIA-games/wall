<?php

namespace App\Http\Controllers;
use App\Models\subscription;
use Illuminate\Http\Request;

class subscriptionCotroller extends Controller
{
    function getsubscription(){

       $month = subscription::where('id',1)->first();

       
       $year = subscription::where('id',2)->first();
          
       return view('Subscription.subscription',['data1'=>$month,'data2'=>$year]);

    }

    function editMonthSub(Request $req){

        $month = subscription::find($req->id);

        $month->price = $req->price;
        $month->title = $req->title;
        $month->validity = $req->validity;
        $month->android = $req->android;
        $month->ios = $req->ios;
        $month->currency = $req->currency;

       $result = $month->save();

       if($result == 1){
           return json_encode(['status'=>true,'message'=>'Upadte Successfull']);
       }

    }


    function editYearSub(Request $req){

        $month = subscription::find($req->id);

        $month->price = $req->price;
        $month->title = $req->title;
        $month->validity = $req->validity;
        $month->android = $req->android;
        $month->ios = $req->ios;
        $month->currency = $req->currency;

       $result = $month->save();

       if($result == 1){
           return json_encode(['status'=>true,'message'=>'Upadte Successfull']);
       }

    }

    function allSubscription(){

        $data = subscription::get();
        return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);

    }
}
