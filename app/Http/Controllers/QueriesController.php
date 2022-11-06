<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Queries;

class QueriesController extends Controller
{
  function addQueries(Request $req){

             $rules = [
              'title' => 'required',
              'description'=> 'required',
              'image'=> 'required'
          ];
          $validator = Validator::make($req->all(), $rules);
          if ($validator->fails()) {
              $messages = $validator->errors()->all();
              $msg = $messages[0];
              return response()->json(['status' => 401, 'message' => $msg]);
          }


          $query = new Queries();

          $query->title = $req->title;
          $query->description = $req->description;

          $path = $req->file('image')->store('uploads');
          $query->image = $path;
          $reslut =$query->save();

          if($reslut){
              return json_encode(['status'=>true,'meassage'=>'Add Successfull']);
          }

  }


  function fetchAllquery(Request $request){

    $totalData =  Queries::where('solved',0)->count();
    $rows = Queries::where('solved',0)->orderBy('id', 'DESC')->get();


    $categories = $rows;

    $columns = array(
        0 => 'id',
        1 => 'title'
    );
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');
    $totalData = Queries::where('solved',0)->count();
    $totalFiltered = $totalData;
    if (empty($request->input('search.value'))) {
        $categories = Queries::where('solved',0)->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    } else {
        $search = $request->input('search.value');
        $categories =  Queries::where('solved',0)->Where('title', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
        $totalFiltered =Queries::where('solved',0)->where('id', 'LIKE', "%{$search}%")
            ->orWhere('title', 'LIKE', "%{$search}%")
            ->count();
    }
    $data = array();
    foreach ($categories as $da) {
 

    
        $data[] = array(
         
            '<img src="public/storage/'.$da->image.'" width="100" height="100">',
            '<p>'.$da->title.'</p>',
            '<button type="button" class="btn btn-primary video-btn" data-toggle="modal" data-src="'.$da->description.'" data-target="#video_call_modal"><i class="fas fa-eye"></i></button>`',
            '<a href = ""  rel = "'.$da->id.'" class = "btn btn-danger solvedquery text-white" > <i class="fas fa-check"></i> </a>',
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


  function fetchSolvedquery(Request $request){

    $totalData =  Queries::where('solved',1)->count();
    $rows = Queries::where('solved',1)->orderBy('id', 'DESC')->get();


    $categories = $rows;

    $columns = array(
        0 => 'id',
        1 => 'title'
    );
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');
    $totalData = Queries::where('solved',1)->count();
    $totalFiltered = $totalData;
    if (empty($request->input('search.value'))) {
        $categories = Queries::where('solved',1)->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
    } else {
        $search = $request->input('search.value');
        $categories =  Queries::where('solved',1)->Where('title', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
        $totalFiltered =Queries::where('solved',1)->where('id', 'LIKE', "%{$search}%")
            ->orWhere('title', 'LIKE', "%{$search}%")
            ->count();
    }
    $data = array();
    foreach ($categories as $da) {
 

    
        $data[] = array(
         
            '<img src="public/storage/'.$da->image.'" width="100" height="100">',
            '<p>'.$da->title.'</p>',
            '<button type="button" class="btn btn-primary video-btn" data-toggle="modal" data-src="'.$da->description.'" data-target="#video_call_modal"><i class="fas fa-eye"></i></button>`',
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


  function solvedQuery($id){

       $query = Queries::find($id);

       $query->solved = 1;

       $reslut = $query->save();

       if($reslut){
           return json_encode(['status'=>true,'meassage'=>'Solved Successfull']);
       }

  }
}
