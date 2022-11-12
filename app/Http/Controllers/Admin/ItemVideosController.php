<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Log;
use App\Models\ItemVideo;

class ItemVideosController extends Controller
{
    //

    public function itemVideos(Request $request){


        $items=Item::orderby('name','asc')->pluck('name','id')->toArray();
        $search_category="";
        $search_item="";

       if($request->isMethod('post')) {
           $search_category=$request->search_video_category;
           $search_item=$request->search_item;
           $itemvideos=ItemVideo::join('items','item_videos.item_id','=','items.id')
           ->when(!empty($search_category),function($query)use($search_category){

            $query->where('item_videos.video_category',$search_category);

           })
           ->when(!empty($search_item),function($query)use($search_item){
               
            $query->where('item_videos.item_id',$search_item);

        })
           ->select( 'item_videos.id','items.name as item_name','item_videos.video_category','item_videos.title','item_videos.video_file_name')->get();
   

       }
       else{
        $itemvideos=ItemVideo::join('items','item_videos.item_id','=','items.id')->select( 'item_videos.id','items.name as item_name','item_videos.title','item_videos.video_category','item_videos.video_file_name')->get();
   
       }
 
        return view('admin.item.itemVideos', ['items'=>$items,'itemvideos'=>$itemvideos,'search_category'=> $search_category,'search_item'=>     $search_item]);
    }



    public function addUpdateItemVideo(Request $request){
 
        $item_id=$request->item_id;
        $video_category=$request->video_category;
        $title=$request->title;
        $description=$request->description;
  
         $id=$request->id;
 
         if(!empty($id)){

            $previous_uploaded_detail= ItemVideo::where('id',$id)->select('video_file_name','thumbnail_image')->first();


            $previous_uploaded_video= $previous_uploaded_detail->video_file_name;
            $previous_uploaded_image= $previous_uploaded_detail->thumbnail_image;
         }
         else{
            $previous_uploaded_video="";
            $previous_uploaded_image="";
         }


 
            if($request->hasFile('upload_video')   ){

                $request->validate([
                    'upload_video'  => 'mimes:mp4,mov,ogg,qt | max:20000',
                    ]);

                    $uploaded_video=$request->file('upload_video');
                
                $destinationPath = public_path('itemVideos'); // upload path
                $uploaded_video_file = time() . "." .  $uploaded_video->getClientOriginalExtension();
                $uploaded_video->move($destinationPath, $uploaded_video_file);
            
            }
            else{
                $uploaded_video_file=$previous_uploaded_video;
            }



            if($request->hasFile('upload_image')   ){

                $request->validate([
                    'upload_image'  => 'mimes:jpeg,png,jpg,gif,svg|max:20000', 
                    ]);

                    $uploaded_image=$request->file('upload_image');
                
                $destinationPath = public_path('itemVideoThumbnails'); // upload path
                $uploaded_image_file = time() . "." .  $uploaded_image->getClientOriginalExtension();
                $uploaded_image->move($destinationPath, $uploaded_image_file);
            
            }
            else{
                $uploaded_image_file=$previous_uploaded_image;
            }
  
            if(empty($id)){
 
                ItemVideo::create([ 'title'=>$title,'description'=>$description ,'item_id'=> $item_id,'video_category'=> $video_category,'video_file_name'=>$uploaded_video_file, 'thumbnail_image'=>    $uploaded_image_file]);
                $message="Item Video Added successfully";
 
            }
            else{ 
                 
                ItemVideo::where('id',$id)->update([ 'title'=>$title,'description'=>$description ,'item_id'=> $item_id, 'thumbnail_image'=>    $uploaded_image_file,'video_category'=> $video_category,'video_file_name'=>$uploaded_video_file]);
                $message="Item Video Update successfully";
            }
            
            return redirect()->back()->with('success', $message);;
    }


    public function deleteItemVideo($delete_id){


          ItemVideo::where('id',$delete_id)->delete();

          $message="Item Video deleted successfully";

          return response()->json(['status'=>true]);


    }



    public function getItemVideoDetail($video_id){

        $video_detail= ItemVideo::where('id',$video_id)->first();

        $video_url=asset('itemVideos/'.  $video_detail->video_file_name);
        $image_url=asset('itemVideoThumbnails/'.  $video_detail->thumbnail_image);
        $video_detail->video_file_name=   $video_url;
        $video_detail->thumbnail_image=    $image_url;

        return response()->json(['item_video_detail'=>$video_detail]);
 

    }
}
