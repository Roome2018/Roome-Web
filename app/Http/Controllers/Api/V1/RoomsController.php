<?php

namespace App\Http\Controllers\Api\V1;

use App\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoomsRequest;
use App\Http\Requests\Admin\UpdateRoomsRequest;
use App\Http\Controllers\Traits\FileUploadTrait;

class RoomsController extends Controller
{
    use FileUploadTrait;

    public function index(Request $request)
    {
        $page = (isset($request->page) ? $request->page : 1) ;
        $page = (($page <= 0) ? 1 : $page ) ;
        $limit = (isset($request->page_count) ? $request->page_count : 20 ) ;
        $skip =  ($page-1) * $limit ;
        $skip = abs( $skip);

        $items = Room::with('tenants');

        $pages_count = ceil(count($items->get()) / $limit);
        $items = $items->skip($skip)->take($limit)->get();
        foreach ($items as $item){
            foreach ($item->getMedia('photos') as $media) {
                $media['url']= asset('public'.$media->getUrl());
            }
        }

        $message ='Successful';
        $status  = true;
        $response = ['status' => $status ,'message' =>$message ,'items' =>$items,'page'=>$page , 'pages_count'=>$pages_count ];
        return response()->json($response);
    }

    public function show($id)
    {
        return Room::findOrFail($id);
        $item =Room::where('id',$item->id)->with('media')->first();
        if ($item){
            $status   = true;
            $message  = "Succefull";

        }else{
            $status  =false;
            $message ="Erorr No Rooms  inserted yet";
        }
        $response = ['status' => $status ,'message' =>$message ,'items' =>$item ];
        return response()->json($response);
    }

    public function update(UpdateRoomsRequest $request, $id)
    {
        $request = $this->saveFiles($request);
        $room = Room::findOrFail($id);
        $room->update($request->all());
        

        return $room;
    }


    public function store(StoreRoomsRequest $request)
    {
        $request = $this->saveFiles($request);
        $item = Room::create($request->all());
        if($item){
            //  dd($item->id);
            foreach ($request->input('photos_id', []) as $index => $id) {
                $model          = config('laravel-medialibrary.media_model');
                $file           = $model::find($id);
                $file->model_id = $item->id;
                $file->save();

            }
            $item_a =Room::where('id',$item->id)->with('media')->first();
            $status   = true;
            $message  = "Succefull";

        }else{
            $status  =false;
            $message ="Erorr No Items  inserted yet";
        }
        $response = ['status' => $status ,'message' =>$message ,'items' =>$item_a ];
        return response()->json($response);
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return '';
    }
}
