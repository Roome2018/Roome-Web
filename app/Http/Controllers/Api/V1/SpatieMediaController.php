<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpatieMediaController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if (! $request->has('model_name') && ! $request->has('file_key') && ! $request->has('bucket')) {
            return abort(500);
        }

        $model = 'App\\' . $request->input('model_name');
        try {
            $model = new $model();
        } catch (ModelNotFoundException $e) {
            abort(500, 'Model not found');
        }

        $files      = $request->file($request->input('file_key'));
        $addedFiles = [];
        foreach ($files as $file) {
            try {
                $model->exists     = true;
                $media             = $model->addMedia($file)->toMediaCollection($request->input('bucket'));
                $addedFiles[]      = $media;
            } catch (\Exception $e) {
                abort(500, 'Could not upload your file');
            }
        }
//        dd($media['id']);
        $media['file_name'] = asset('uploads/media/'.$media['id'].'/'.$media['file_name']);
        $addedFiles = (object)$media;
        if($addedFiles){
            $status   = true;
            $message  = "Succefull";

        }else{
            $status  =false;
            $message ="Erorr No Data inserted yet";
        }
        $response = ['status' => $status ,'message' =>$message ,'items' =>$addedFiles ];
        return response()->json($response);
        //return response()->json(['files' => $addedFiles]);
    }
}
