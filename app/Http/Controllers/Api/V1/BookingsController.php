<?php

namespace App\Http\Controllers\Api\V1;

use App\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookingsRequest;
use App\Http\Requests\Admin\UpdateBookingsRequest;
use Illuminate\Support\Facades\Auth;

class BookingsController extends Controller
{
    public function index()
    {
        return Booking::all();
    }

    public function show($id)
    {
        return Booking::findOrFail($id);
    }

    public function update(UpdateBookingsRequest $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update($request->all());
        

        return $booking;
    }

    public function store(StoreBookingsRequest $request)
    {
        $user = Auth::guard('api')->user() ;
        $request['user_id']= $user->id;
        $request['status']= 0;
//        $request['date']= $user->id;

        $item = Booking::create($request->all());


        if($item){
            //  dd($item->id);

            $item =Booking::where('id',$item->id)->with('room')->first();
            $status   = true;
            $message  = "Succefull";

        }else{
            $status  =false;
            $message ="Erorr No Items  inserted yet";
        }
        $response = ['status' => $status ,'message' =>$message ,'items' =>$item ];
        return response()->json($response);

    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return '';
    }
}
