<?php

namespace App\Http\Controllers\Api\Customer\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\ItemVideo;
use App\Models\ItemVideoComment;

class ItemVideoApiController  extends AppBaseController
{
    

    public function itemVideos($item_id){

       $live_videos=  ItemVideo::where('video_category','Live')->where('item_id',$item_id)->orderby('id','desc')->pluck('video_file_name') ;
       
       $past_videos=  ItemVideo::where('video_category','Past')->where('item_id',$item_id)->orderby('id','desc')->pluck('video_file_name') ;

       $upcoming_videos=  ItemVideo::where('video_category','Upcoming')->where('item_id',$item_id)->orderby('id','desc')->pluck('video_file_name') ;

       return $this->sendResponse(['live'=> $live_videos,'past'=> $past_videos,'upcoming'=>$upcoming_videos,'base_url'=>asset('itemVideos')],'');
 
    }


    public function itemVideosByCategory($item_id,$video_category){
 
        $searched_videos=  ItemVideo::where('video_category',$video_category)->where('item_id',$item_id)->orderby('id','desc')->pluck('video_file_name')->toArray();
        
        return $this->sendResponse(['searched_videos'=>$searched_videos],'');
    }

    public function addCommentToItemVideo(Request $request,$item_video_id){
 

        $user_id=\Auth::user()->id;

        $comment=$request->comment;

        if(empty( $comment)){
            return response()->json(['status'=>'no comment given']);
        }

        $user_video_comment_exists= ItemVideoComment::where(['item_video_id'=> $item_video_id,'user_id'=> $user_id])->exists();


        if($user_video_comment_exists==true){
            return response()->json(['status'=>'user cannot comment more than one']);
        }

        ItemVideoComment::create(['item_video_id'=> $item_video_id,'user_id'=> $user_id,'comment'=>   $comment]);

        return $this->sendResponse([],'Comment Added Successfully');
 
    }


    public function getVideoDetailWithComments(Request $request,$item_video_id){
 
         $item_id= ItemVideo::where('id', $item_video_id)->value('item_id');
           $comments=ItemVideoComment::where('item_video_id',$item_video_id)->select('user_id','comment','created_at')->get();
           return $this->sendResponse(['item_id'=>$item_id,'comments'=>$comments],'' );
    }
}
