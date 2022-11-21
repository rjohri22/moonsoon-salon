<?php

namespace App\Http\Controllers\Api\Customer\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\ItemVideo;
use App\Models\ItemVideoComment;
use DB;

class ItemVideoApiController  extends AppBaseController
{
    

    public function itemVideos($item_id=null){

        $video_path=asset('itemVideos/');;

        $thumbnail_path =asset('itemVideoThumbnails/');

       $live_videos=  ItemVideo::join('items','item_videos.item_id','=','items.id')->where('video_category','Live')->when(!empty($item_id),function($query)use($item_id){
        $query->where('item_videos.item_id',$item_id);
       })->orderby('id','desc')->select( 'items.id as item_id' ,'items.name as item_name','item_videos.title','item_videos.id','item_videos.description',DB::raw(" CONCAT('". $video_path."' ,'/',item_videos.video_file_name)  as video_url "),DB::raw(" CONCAT('". $thumbnail_path."' ,'/',item_videos.thumbnail_image)  as image_url "))->get() ;
       
       $past_videos= ItemVideo::join('items','item_videos.item_id','=','items.id')->where('video_category','Past')->when(!empty($item_id),function($query)use($item_id){
        $query->where('item_videos.item_id',$item_id);
       })->orderby('id','desc')->select('items.id as item_id' , 'items.name as item_name','item_videos.title','item_videos.id','item_videos.description',DB::raw(" CONCAT('". $video_path."' ,'/',item_videos.video_file_name)  as video_url "),DB::raw(" CONCAT('". $thumbnail_path."' ,'/',item_videos.thumbnail_image)  as image_url "))->get() ;


       $upcoming_videos= ItemVideo::join('items','item_videos.item_id','=','items.id')->where('video_category','Upcoming')->when(!empty($item_id),function($query)use($item_id){
        $query->where('item_videos.item_id',$item_id);
       })->orderby('id','desc')->select( 'items.id as item_id' ,'items.name as item_name','item_videos.title','item_videos.id','item_videos.description',DB::raw(" CONCAT('". $video_path."' ,'/',item_videos.video_file_name)  as video_url "),DB::raw(" CONCAT('". $thumbnail_path."' ,'/',item_videos.thumbnail_image)  as image_url "))->get() ;


       return $this->sendResponse(['live'=> $live_videos,'past'=> $past_videos,'upcoming'=>$upcoming_videos],'');
 
    }


    public function itemVideosByCategory($item_id,$video_category){
 
        $searched_videos=  ItemVideo::where('video_category',$video_category)->where('item_id',$item_id)->orderby('id','desc')->pluck('video_file_name')->toArray();
        
        return $this->sendResponse(['searched_videos'=>$searched_videos],'');
    }

    public function addCommentToItemVideo(Request $request,$item_video_id){
 

        // $user_id=\Auth::user()->id;
        $user_id=$request->user_id;
        $comment=$request->comment;

        if(empty( $comment)){
            return response()->json(['status'=>'no comment given']);
        }

        $user_video_comment_exists= ItemVideoComment::where(['item_video_id'=> $item_video_id,'user_id'=> $user_id])->exists();
 
        ItemVideoComment::create(['item_video_id'=> $item_video_id,'user_id'=> $user_id,'comment'=>   $comment]);

        return $this->sendResponse([],'Comment Added Successfully');
 
    }


    public function getVideoDetailWithComments(Request $request,$item_video_id){
 
         $item_id= ItemVideo::where('id', $item_video_id)->value('item_id');
           $comments=ItemVideoComment::where('item_video_id',$item_video_id)->select('user_id','comment','created_at')->get();
           return $this->sendResponse(['item_id'=>$item_id,'comments'=>$comments],'' );
    }


    public function getSingleItemVideoDetail(Request $request){

        $video_id= $request->video_id;
        
        $video_path=asset('itemVideos/');;

       $thumbnail_path =asset('itemVideoThumbnails/');;

       $videodetail= ItemVideo::join('items','items.id','=','item_videos.item_id')->where('item_videos.id',$video_id)->select('items.name as item_name','item_videos.id','item_videos.item_id','item_videos.video_category',DB::raw(" CONCAT('". $video_path."' ,'/',item_videos.video_file_name)  as video_url "),DB::raw(" CONCAT('". $thumbnail_path."' ,'/',item_videos.thumbnail_image)  as image_url "),'title','item_videos.description')->get();
 
       $videodetail=$this->addComments(  $videodetail);
        // $upcoming= ItemVideo::join('items','items.id','=','item_videos.item_id')->where('video_category','Upcoming')->where('items.id',$item_id)->select('items.name as item_name','item_videos.id','item_videos.item_id','item_videos.video_category',DB::raw(" CONCAT('". $video_path."' ,'/',item_videos.video_file_name)  as video_url "),DB::raw(" CONCAT('". $thumbnail_path."' ,'/',item_videos.thumbnail_image)  as image_url "),'title','item_videos.description')->get();

        // $live= ItemVideo::join('items','items.id','=','item_videos.item_id')->where('video_category','Live')->where('items.id',$item_id)->select('items.name as item_name','item_videos.id','item_videos.item_id','item_videos.video_category',DB::raw(" CONCAT('". $video_path."' ,'/',item_videos.video_file_name)  as video_url "),DB::raw(" CONCAT('". $thumbnail_path."' ,'/',item_videos.thumbnail_image)  as image_url "),'title','item_videos.description')->get();

        // $past= ItemVideo::join('items','items.id','=','item_videos.item_id')->where('video_category','Past')->where('items.id',$item_id)->select('items.name as item_name','item_videos.id','item_videos.item_id','item_videos.video_category',DB::raw(" CONCAT('". $video_path."' ,'/',item_videos.video_file_name)  as video_url "),DB::raw(" CONCAT('". $thumbnail_path."' ,'/',item_videos.thumbnail_image)  as image_url "),'title','item_videos.description')->get();

        // $upcoming=$this->addComments(  $upcoming);
        // $live= $this->addComments( $live);
        // $past= $this->addComments($past);

        // return response()->json(['upcoming'=>  $upcoming,'live'=>$live,'past'=>$past]);

        
        return $this->sendResponse(['data'=>$videodetail],'');
 
    }

    public function addComments($videos){

        $index=0;
        foreach($videos as $video){

           $comments= ItemVideoComment::join('users','item_video_comments.user_id','=','users.id')->orderby('item_video_comments.id','asc')->where('item_video_id',$video->id)->select(DB::raw("CONCAT(first_name,' ',last_name) as name"),'comment as message',DB::raw("DATE_FORMAT(item_video_comments.created_at , '%d/%m/%Y %h:%i %p') as timeDate "))->get();
           
           $videos[$index]->comments= $comments;
           $index++;

        }
 
        return $videos;

    }
}
