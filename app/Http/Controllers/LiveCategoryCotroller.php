<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiveCategory;
use App\Models\Wallpaper;

class LiveCategoryCotroller extends Controller
{
    function fetchAllCategory(Request $request){

        $totalData =  LiveCategory::count();
        $rows = LiveCategory::orderBy('id', 'DESC')->get();


        $categories = $rows;

        $columns = array(
            0 => 'id',
            1 => 'title'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = LiveCategory::count();
        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $categories = LiveCategory::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $categories =  LiveCategory::Where('title', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered =LiveCategory::where('id', 'LIKE', "%{$search}%")
                ->orWhere('title', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        foreach ($categories as $cat) {
     

              $id =$cat->id;
              $news_count=  Wallpaper::Where('wallpaper_type',1)->where('category_id',$id)->count();

            $data[] = array(
             
                '<img src="public/storage/'.$cat->image.'" width="100" height="100">',
             '<p>'.$cat->title.'</p>',
             '<a  class="badge badge-success  text-white px-4 py-2 ">'.$news_count.'</a>',

             '<a href="" data-toggle="modal" id="'.$cat->id.'" rel="'.$cat->image.'"  data-id="'.$cat->title.'" data-target="#edit_cat_modal" class="btn btn-primary  edit_cats"><i class="fas fa-edit"></i></a>',
             '<a href = ""  rel = "'.$cat->id.'" class = "btn btn-danger delete-cat text-white" > <i class="fas fa-trash-alt"></i> </a>',
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

    function deleteCat($id){

           $data =  LiveCategory::where('id',$id);
           $data->delete();
           
            Wallpaper::where('category_id',$id)->where('wallpaper_type',1)->delete();
           
           $data1['status'] = true;
           $data1['message'] = "delete successfull";
  
           echo json_encode($data1);

    }

    function addCat(Request $req){

        $cat = new LiveCategory();
      
         $cat->title = $req->title;
        
         $path = $req->file('image')->store('uploads');
         $cat->image = $path;
         $cat->save();
         $data['status'] = true;
         $data['message'] = "add successfull";

         echo json_encode($data);

    }

    function updateCat(Request $req){

     



        if($req->image == ""){
            LiveCategory::where('id', $req->id)->update(['title' => $req->title]);
        }else{
            $path = $req->file('image')->store('uploads');
            LiveCategory::where('id', $req->id)->update(['title' => $req->title,'image' => $path ]);
          
        }

        $data['status'] = true;
        $data['message'] = "update successfull";

        echo json_encode($data);

    }

    
    function livecategory(){
        
        $rows = LiveCategory::orderBy('id','DESC')->get();
  
      
        $data['cats'] = $rows;
        $data['status'] = true;
        $data['message'] = "all data fetch successfull";
        echo json_encode($data);
     
     }

     function getLiveCategory(){
         
         
       $row1 = LiveCategory::orderBy('id','DESC')->get();


        foreach ($row1 as $cat) {

           $cat_id = $cat->id;
           $row = Wallpaper::Where('wallpaper_type',1)->where('category_id',$cat_id)->count();
           $cat['count'] = $row;
           $data[] = $cat;
            
        }

        return json_encode(['status'=>true,'message'=>'all data fetch successfull','data'=>$data]);


     }

}
