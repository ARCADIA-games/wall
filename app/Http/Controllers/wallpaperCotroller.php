<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Wallpaper;

class wallpaperCotroller extends Controller
{

    function jk(){

        return   Wallpaper::where('wallpaper_type',1)->with("livecategory")->get();
    }
    

    function addWallpaper(Request $req){


        foreach($req->file('image') as $image){


            $wallpaper = new Wallpaper();  

         
            $path = $image->store('uploads');

            $wallpaper->type = $req->type;
            $wallpaper->image = $path ;
            $wallpaper->tags = $req->tags;
            $wallpaper->category_id = $req->category_id;

            $wallpaper->save();

        }

        return json_encode(['status'=>true,'message'=>'add successfull']);
    }


    function addLiveWallpaper(Request $req){


        
        $wallpaper = new Wallpaper();  
       
        $path = $req->file('image')->store('uploads');
    
        $videopath =  $req->file('video')->store('uploads');


        $wallpaper->type = $req->type;
        $wallpaper->wallpaper_type = $req->wallpaper_type;
        $wallpaper->image = $path ;
        $wallpaper->video = $videopath ;
        $wallpaper->tags = $req->tags;
        $wallpaper->category_id = $req->category_id;

        $wallpaper->save();

        return json_encode(['status'=>true,'message'=>'add successfull']);


    }

    function fetchAllWallpaper(Request $request){


        $totalData =  Wallpaper::where('wallpaper_type',0)->count();
        $wallpaper =  Wallpaper::where('wallpaper_type',0)->with("category")->get();
  
        $columns = array(
            0 => 'id',
            1 => 'tags'
        );
  
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
  
        $totalData = Wallpaper::where('wallpaper_type',0)->count();
  
        $totalFiltered = $totalData;
  
        if (empty($request->input('search.value'))) {
            $wallpaper = Wallpaper::where('wallpaper_type',0)->with("category")->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
  
            $wallpaper =   Wallpaper::where('wallpaper_type',0)->with("category")->orwhere('tags', 'LIKE', "%{$search}%")->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
  
            $totalFiltered = Wallpaper::where('wallpaper_type',0)
                ->orWhere('tags', 'LIKE', "%{$search}%")
                ->count();
        }
  
        $data = array();
  
        foreach ($wallpaper as $item) {
  
            $url = asset('public/storage/' . $item->image);
  
  
            
  
            $data[] = array(
  
              '<img src="'.$url.'" width="100" height="100">',
            //   $item->tags,
              $item->category->title,
              $item->tags,
              $item->type,
              '<a href="#" rel="'.$item->id.'" class = "btn btn-primary editWallpaper  " data-toggle="modal" data-target="#editwallpaper" > <i class="fas fa-edit"></i> </a>',
              '<a rel = '.$item->id.' class = "btn btn-danger delete-wallpaper text-white" > <i class="fas fa-trash-alt"></i> </a>'
  
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
  
        echo json_encode($json_data);
        exit();
  

    }


    function fetchLiveWallpaper(Request $request){


        $totalData =  Wallpaper::where('wallpaper_type',1)->count();
        $wallpaper =  Wallpaper::where('wallpaper_type',1)->with("livecategory")->get();
  
        $columns = array(
            0 => 'id',
            1 => 'tags'
        );
  
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
  
        $totalData = Wallpaper::where('wallpaper_type',1)->count();
  
        $totalFiltered = $totalData;
  
        if (empty($request->input('search.value'))) {
            $wallpaper = Wallpaper::where('wallpaper_type',1)->with("livecategory")->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
  
            $wallpaper =   Wallpaper::where('wallpaper_type',1)->with("livecategory")->where('tags', 'LIKE', "%{$search}%")->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
  
            $totalFiltered = Wallpaper::where('wallpaper_type',1)
                ->where('tags', 'LIKE', "%{$search}%")
                ->count();
        }
  
        $data = array();
  
        foreach ($wallpaper as $item) {
  
            $url = asset('public/storage/' . $item->image);
            $videourl = asset('public/storage/' . $item->video);
  
            
  
            $data[] = array(
  
              '<img src="'.$url.'" width="100" height="100">',
            //   $item->tags,
              $item->livecategory->title,
              $item->tags,
              $item->type,
              '<button type="button" class="btn btn-primary video-btn" data-toggle="modal" data-src="'.$videourl.'" data-target="#video_call_modal"><i class="fas fa-video"></i></button>`',
              '<a href="#" rel="'.$item->id.'" class = "btn btn-primary editWallpaper  " data-toggle="modal" data-target="#editwallpaper" > <i class="fas fa-edit"></i> </a>',
              '<a rel = '.$item->id.' class = "btn btn-danger delete-wallpaper text-white" > <i class="fas fa-trash-alt"></i> </a>'
  
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
  
        echo json_encode($json_data);
        exit();
  

    }

    function viewallpaper($id){

      $data =   Wallpaper::where('id',$id)->with('category')->first();

      return json_encode(['status'=>true,'message'=>'fecth successfull','data'=>$data]);

    }

    function editWallpaper(Request $req){
 

        $wallpaper =  Wallpaper::find($req->id);
         
        if($req->image != ""){
           

        $path = $req->file('image')->store('uploads');

        $wallpaper->image = $path;

        }

        if($req->has('video')){

        if($req->video != ""){
            $pathvideo = $req->file('video')->store('uploads');

        $wallpaper->video = $pathvideo;

        }
    }



        $wallpaper->type = $req->type;
        $wallpaper->tags = $req->tags;
        $wallpaper->category_id = $req->category_id;

        $wallpaper->save();

       return json_encode(['status'=>true,'message'=>'update successfull']);



    }

    function deleteWallpaper($id){

        $data =  Wallpaper::where('id',$id);
        $data->delete();
        
        $data1['status'] = true;
        $data1['message'] = "delete successfull";

        echo json_encode($data1);

 }


 function getWallpaper(Request $req){

    if($req->has('start')){

        $start = $req->start;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'start not found']);
    }
  
    if($req->has('count')){
  
        $count = $req->count;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'=count not found']);
    }

    $data = Wallpaper::where('wallpaper_type',0)->skip($start)->take($count)->with("category")->orderBy('id','DESC')->get();

    return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);

}

function getAllWallpaper(Request $req){

    if($req->has('start')){

        $start = $req->start;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'start not found']);
    }
  
    if($req->has('count')){
  
        $count = $req->count;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'=count not found']);
    }


    $data = Wallpaper::skip($start)->take($count)->orderBy('id','DESC')->get();

    return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);

}


function getWallpaperByCatId(Request $req){

    if($req->has('start')){

        $start = $req->start;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'start not found']);
    }
  
    if($req->has('count')){
  
        $count = $req->count;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'=count not found']);
    }

    if($req->has('cat_id')){

        $data = Wallpaper::where('wallpaper_type',0)->where('category_id',$req->cat_id)->skip($start)->take($count)->with("category")->orderBy('id','DESC')->get();

        return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);

    }else{
        return json_encode(['status'=>false,'message'=>'cat_id not found']);

    }


    

}




function getLiveWallpaper(Request $req){

    if($req->has('start')){

        $start = $req->start;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'start not found']);
    }
  
    if($req->has('count')){
  
        $count = $req->count;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'=count not found']);
    }


    $data = Wallpaper::where('wallpaper_type',1)->skip($start)->take($count)->with("livecategory")->orderBy('id','DESC')->get();

    return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);

}


function getLiveWallpaperByCatId(Request $req){


    if($req->has('start')){

        $start = $req->start;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'start not found']);
    }
  
    if($req->has('count')){
  
        $count = $req->count;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'=count not found']);
    }


    if($req->has('cat_id')){
    
        $data = Wallpaper::where('wallpaper_type',1)->where('category_id',$req->cat_id)->skip($start)->take($count)->with("livecategory")->orderBy('id','DESC')->get();

        return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);

    }else{
        return json_encode(['status'=>false,'message'=>'cat_id not found']);
    }

}

function getWallpaperById(Request $req){

    if($req->has('id')){
    
        $row = Wallpaper::where('id',$req->id)->first();

        if($row['wallpaper_type'] == 0){
            $data = Wallpaper::where('id',$req->id)->with('category')->first();
        }else{
            $data = Wallpaper::where('id',$req->id)->with('livecategory')->first();
        }
        

        return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);

    }else{
        return json_encode(['status'=>false,'message'=>'id not found']);
    }

}

function searchWallpaper(Request $req){


    if($req->has('start')){

        $start = $req->start;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'start not found']);
    }
  
    if($req->has('count')){
  
        $count = $req->count;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'=count not found']);
    }
   

    if($req->has('search')){

        $search = $req->search;

    }else{
        return json_encode(['status'=>false,'message'=>'search not found']);
    }


    if($req->has('type')){

        $type = $req->type;

    }else{
        return json_encode(['status'=>false,'message'=>'type not found']);
    }


    if($type == 'None'){

        $data = Wallpaper::where('tags', 'LIKE', "%{$search}%")->skip($start)->take($count)->orderBy('id','DESC')->get();

    }else{
        $data = Wallpaper::where('type',$type)->where('tags', 'LIKE', "%{$search}%")->skip($start)->take($count)->orderBy('id','DESC')->get();
    }


    return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);
}
  
function downloadCount(Request $req){

    if($req->has('id')){

        $id = $req->id;

    }else{
        return json_encode(['status'=>false,'message'=>'id not found']);
    }

    Wallpaper::where('id', $req->id)->increment('dowonload_count', 1);
    
    return json_encode(['status'=>true,'message'=>'all data fetch successfull']);
    

}

function getWpWithCatIdDown(Request $req){

    if($req->has('category_id')){

        $category_id = $req->category_id;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'category_id not found']);
    }

    $data = Wallpaper::where('category_id',$category_id)->where('wallpaper_type',0)->with('category')->orderBy('dowonload_count','DESC')->limit(10)->get();

    return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);


}


function getLiveWpwithCatIdDown(Request $req){

    if($req->has('category_id')){

        $category_id = $req->category_id;
  
    } else{
  
        return json_encode(['status'=>false ,'message'=>'category_id not found']);
    }

    $data = Wallpaper::where('category_id',$category_id)->where('wallpaper_type',1)->with('livecategory')->orderBy('dowonload_count','DESC')->limit(10)->get();

    return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);


}


function getAllWpWithDown(Request $req){

   

    $data = Wallpaper::orderBy('dowonload_count','DESC')->limit(10)->get();

    return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);


}


function getWpWithDown(Request $req){

    $data = Wallpaper::where('wallpaper_type',0)->with('category')->orderBy('dowonload_count','DESC')->limit(10)->get();

    return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);


}

function getLiveWpWithDown(Request $req){

    $data = Wallpaper::where('wallpaper_type',1)->with('livecategory')->orderBy('dowonload_count','DESC')->limit(10)->get();

    return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);


}

function favouriteWallpaper(Request $req){


  $data2 = $req->json()->all();

    $result = $data2['ids'];

    $data = Wallpaper::whereIn('id', $result)->get();

    return json_encode(['status'=>true ,'message'=>'All Data Fetch Successfull','data'=> $data]);
      
          

  }


}
