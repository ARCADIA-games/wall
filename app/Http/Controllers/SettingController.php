<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    
 function setting(){
        $admobs = DB::table('admob')->get();
        $misc = DB::table('misc')->get();
     return   view('setting.setting',['admobs'=>$admobs,'misc'=>$misc]);
    }
    function getAdmob()
    {
        $data['admobs'] = DB::table('admob')->get();
        echo json_encode($data);
    }

    function getFb()
    {
        $data['fbs'] = DB::table('facebook')->get();
        echo json_encode($data);
    }

    function getMisc()
    {
        $data['miscs'] = DB::table('misc')->get();
        echo json_encode($data);
    }

    function getSocial()
    {
        $data['socials'] = DB::table('social')->get();
        echo json_encode($data);
    }


    function updateAdmob(Request $req){

        $id = $req->id;


        $data  =   DB::table('admob')->where('id', $id)->update(['publisher_id' => $req->publisher_id,
                                                        'admob_app_id' => $req->admob_app_id,
                                                         'banner_id' => $req->banner_id,
                                                             'intersial_id' => $req->intersial_id,
                                                             'native_id' => $req->native_id,
                                                             'rewarded_id' => $req->rewarded_id
                                                             ]);
        echo json_encode($data);
    }

    function updateFb(Request $req){
        $id = $req->id;

        $data  =   DB::table('facebook')->where('id', $id)->update(['facebook_app_id' => $req->facebook_app_id,
                                                        'fb_banner_id' => $req->fb_banner_id,
                                                         'fb_intersial_id' => $req->fb_intersial_id,
                                                             'fb_native_id' => $req->fb_native_id,
                                                             'fb_rewarded_id' => $req->fb_rewarded_id
                                                             ]);
        echo json_encode($data);
    }

    function updateMisc(Request $req){

        $id = $req->id;
  $data  =   DB::table('misc')->where('id', $id)->update(['more_app' => $req->more_app,
                                                        'privcy_url' => $req->privcy_url,
                                                         'terms' => $req->terms,
                                                         'help_url' => $req->help_url
                                                         ]);
        echo json_encode($data);
    }

    function updateSocial(Request $req){
    
        $id = $req->id;
        $data  =   DB::table('social')->where('id', $id)->update(['facebook' => $req->facebook,
                                                        'you_tube' => $req->you_tube,
                                                         'instagram' => $req->instagram,
                                                         'twitter' => $req->twitter
                                                         ]);
        echo json_encode($data);
    }

    function allSettingData(Request $req){


        
    if($req->has('type')){

        $type = $req->type;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'type not found']);
    }
  

        $admobs = DB::table('admob')->where('type',$req->type)->first();
        $facebooks = DB::table('facebook')->where('type',$req->type)->first();
        $socials = DB::table('social')->where('type',$req->type)->first();
        $miscs = DB::table('misc')->where('type',$req->type)->first();

        return json_encode(['status'=>true ,'message'=>'All Data Fetch Successfull','admobs'=> $admobs,'miscs'=> $miscs,'facebooks'=> $facebooks,'socials'=> $socials]);
    }
}
