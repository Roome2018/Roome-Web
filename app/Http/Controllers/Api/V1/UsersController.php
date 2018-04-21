<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
//use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Device ;
use File ;
use Hash;
use Route ;
use App\Item;
use Illuminate\Support\Facades\Password;


class UsersController extends Controller
{



    protected function access_token(Request $request)
    {
        $request->request->add([
            'grant_type' => $request->grant_type,
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => null,
        ]);
        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        //  dd($proxy) ;

        return Route::dispatch($proxy);
    }

    public function resetpassword(Request $request){

        if (!$request->has('client_id') or !$request->has('client_secret')){

            $status = false;
            $response = ['status' => $status ,'error' =>'forbidden'];
            return response()->json($response);
        }
        $client_app = DB::table('oauth_clients')->get();
        $progress = false ;
        foreach($client_app as $client){
            if ($client->id  == $request->get('client_id') ) $progress = true ;
        }
        //dd($client);
        if($progress and $request->has('email')){
            //dd('alaa');
            $user = User::where('email' ,$request->email)->first() ;
            if($user) {

                /*$credentials = ['email' => $user->email];
                $response = Password::sendResetLink($credentials, function (Message $message) {
                    $message->subject('إعادة تعيين كلمة المرور ');
                });*/
                $response = Password::RESET_LINK_SENT;
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        $status = true;
                        $response = ['status' => $status ];
                        return response()->json($response);

                }

            }
        }


        $status = false;
        $response = ['status' => $status ];
        return response()->json($response);



    }


    public function index(Request $request)
    {

        $user = Auth::guard('api')->user() ;


        $status = true;
        $response = ['status' => $status ,'items' =>$user];
        return response()->json($response);
    }

    public function changePassword(Request $request){
        $user = Auth::guard('api')->user() ;
        if($user) {
            if ($request->has('current_password') and $request->has('new_password')) {
                if (Hash::check( $request->current_password ,$user->password) ) {
                    $user->password = Hash::make($request->new_password);
                    $user->save();
                    $status = true;
                    $response = ['status' => $status];
                    return response()->json($response);
                }else{
                    $status = false;
                    $errors['fieldname'] = 'current_password' ;
                    $errors['message'] = 'not match the current password' ;
                    $response = ['status' => $status ,'errors' =>(object) $errors];
                    return response()->json($response);
                }

            }
        }
        $status = false;
        $response = ['status' => $status];
        return response()->json($response);
    }

    public function userimg(Request $request)
    {
        $user = Auth::guard('api')->user() ;
        $status = false ;

        // dd($request->bb);
        if ($request->hasFile('image_path') and $request->file('image_path')->isValid()) {
            // dd('ttt');
            $remove_prev_image_path = $user->image_path ;
            $file = $request->file('image_path');
            $extension = $file->getClientOriginalExtension();
            //$extension = File::extension($file);
            $fileName = str_random(10) . '_avatar.' . $extension;
            $destinationPath = 'images/avatars/';
            $upload = $request->file('image_path')->move($destinationPath, $fileName);
            if ($upload) {
                $file_profile = $destinationPath . $fileName;
                $user->image_path = $file_profile;
                if ($user->save()) {
                    $status = true;
                    $items['image_path'] =  url($user->image_path) ;
                    $this->remove_img($remove_prev_image_path) ;
                }
            }
        }
        if($request->hasFile('cover_image_path') and $request->file('cover_image_path')->isValid()) {
            $remove_prev_cover = $user->cover_image_path ;
            $file = $request->file('cover_image_path');
            $extension = $file->getClientOriginalExtension();
            //$extension = File::extension($file);
            $fileName = str_random(10) . '_cover.' . $extension;
            $destinationPath = 'images/covers/';
            $upload = $request->file('cover_image_path')->move($destinationPath, $fileName);
            if ($upload) {
                $file_profile = $destinationPath . $fileName;
                $user->cover_image_path = $file_profile;
                if ($user->save()) {
                    $status = true;
                    $items['cover_image_path'] = url($user->cover_image_path)  ;
                    $this->remove_img($remove_prev_cover) ;

                }
            }
        }

        if($status){
            $response = ['status' => $status ,'items' => (object) $items];
        }else{
            $response = ['status' => $status];
        }
        return response()->json($response);


    }



    public function sendsms($phone ,$msg)
    {

        // dd((int)$phone);
        $phone = (int)'965'.$phone ;
        try{
            $url = 'http://www.kwjawsms.com/SMSGateway/Services/Messaging.asmx/Http_SendSMS?username=SEGroup&password=P@ssw0rd&customerId=377&senderText=HiNet%20GCC&messageBody='.$msg.'&recipientNumbers='.$phone.'&defdate=&isBlink=false&isFlash=false';

            // dd($url);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            $result = curl_exec($ch);
            // dd($result);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }  catch (\Exception $e) {
            return $e;
        }

    }

    public function Numeric($length)
    {
        $chars = "1234567890";
        $clen   = strlen( $chars )-1;
        $id  = '';

        for ($i = 0; $i < $length; $i++) {
            $id .= $chars[mt_rand(0,$clen)];
        }
        return ($id);
    }

    public function resendcode(){
        $user = Auth::guard('api')->user();
        $phone = $user->mobile;
        // dd($phone);
        $msg   = $user->mobile_code;
        if($this->sendsms($phone ,$msg)){
            $status = true;
        }else{
            $status = true;
        }
        $response = ['status' => $status ];

        return response()->json($response);

    }
    public function store(Request $request)
    {
        //dd($request);
        //// validate from  app data
        if (!$request->has('client_id') or !$request->has('client_secret')){
            $status = false;
            $response = ['status' => $status ,'error' =>'forbidden'];
            return response()->json($response);
        }
        $client_app = DB::table('oauth_clients')->get();
        $progress = false ;
        foreach($client_app as $client){
            if ($client->id  == $request->get('client_id') ) $progress = true ;
        }
        if($progress){
            $response = User::validateCreateAccount($request);
            if ($response['status']) {

                $user = new User ;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->mobile = $request->mobile;
                $user->password = bcrypt($request->password) ;
                /// check if the user is exist or not
                if ($user->save()) {
                    return  $this->access_token($request) ;

                }
            }else{
                //$status = 2;
                //$response = ['status' => $status ];

                return response()->json($response);
            }

        }
        $status = false;
        $response = ['status' => $status ];

        return response()->json($response);

    }


    public function fixcode(Request $request)
    {
        $user = Auth::guard('api')->user();
        if($user->is_verified == 0){
            $code = (int)$request->code;
            if($request->code == $user->mobile_code){
                $user->is_verified = 1;
                $user->save();
                $status = true;
            }else{
                $status = false;
            }
        }else{
            $status = false;
        }
        $response = ['status' => $status ];

        return response()->json($response);
    }
    public function getifouth(Request $request)
    {
        $user = Auth::guard('api')->user() ;
        $user = User::where('id',$user->id)
//            ->with('connection')
            ->get()
            ->first();
        $status = true;
        $response = ['status' => $status ,'items' =>$user];
        return response()->json($response);
    }

    public function show($id)
    {
        $user = User::find($id) ;
        if ($user) {
            $user = User::where('id',$id)
                ->with('connection')
                ->get()
                ->first();
            $status = true;

            $response = ['status' => $status  ,'items' => $user ];
            return response()->json($response);
        }else{
            $status = false;
            $response = ['status' => $status ,'error' =>'forbidden'];
        }
        return response()->json($response);
    }


    public function updatepassword(Request $request)
    {
        $user = Auth::guard('api')->user() ;
        if($request->oldpassword){
            //dd($request->oldpassword);
            $oldpassword = $request->oldpassword;//app('hash')->needsRehash($request->oldpassword) ? Hash::make($request->oldpassword): $request->oldpassword;
            if (Hash::check($request->oldpassword, $user->password)){
                $user->password =$request->newpassword;
                $user->save();
                $status  = true ;
                $message = 'the password is changed ';
            }else{
                //old password uncorrect
                $status  = false ;
                $message = 'old password is uncorrect  ';
            }
        }

        $response = ['status' => $status ,'message'=>$message];
        return response()->json($response);
    }
    public function uploaduserimage(Request $request)
    {
        $request = $this->saveFiles($request);
        $user = Auth::guard('api')->user() ;
        $userid = $user->id;

        $userimage = User::findOrFail($userid);
        // dd($userimage);
        if($request->image)
            $userimage->image = $request->image;

        if($request->cover)
            $userimage->cover = $request->cover;

        $userimage->save();

        $status = true;
        $response = ['status' => $status ,'items' =>$userimage];
        return response()->json($response);
    }
    public function updateuserimage(Request $request)
    {
        $user = Auth::guard('api')->user() ;
        $userid = $user->id;
        $userimage = User::findOrFail($userid);
        $filename = public_path().'/uploads/'.$userimage->image;
        // dd($userimage->image);
        if($request->image)
            File::delete($filename);


        $request = $this->saveFiles($request);
        //dd($request);
        if($request->image)
            $userimage->image = $request->image;


        $userimage->save();
        $status = true;
        $response = ['status' => $status ,'items' =>$userimage];
        return response()->json($response);
    }



    public function edit($id)
    {
        //
    }
    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user() ;
        $revoke = $user->token()->revoke();
        $status = true ;
        $response = ['status' => $status];
        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //dd($request->all());
        $user = Auth::guard('api')->user() ;
        if($user){
            if ($request->has('status')){
                $user->status = $request->status;
                if($user->status == 0){
                    return  $this->logout( $request);
                }else{
                    $user->status = $request->status;
                    $items['status'] = $user->status ;
                }
            }

            if ($request->has('email')){
                $other_user =User::where('email' ,$request->email)->first();
                if($other_user and $other_user->id != $user->id ){
                    $status = false;
                    $error['fieldname'] ='email' ;
                    $error['message'] ='The email has already been taken.';
                    $response = ['status' => $status ,'errors' => (object) $error];
                    return response()->json($response);

                }
                $user->email = $request->email;
                $items['email'] = $user->email ;

            }

            if ($request->has('first_name')){
                $user->f_name = $request->first_name;
                $items['first_name'] = $user->f_name ;
            }



            if ($request->has('last_name')){
                $user->l_name = $request->last_name;
                $items['last_name'] = $user->l_name ;
            }



            if ($request->exists('name')){
                $user->name = $request->name;
                $items['name'] = $user->name ;

            }

            if ($request->exists('job_title')){
                $user->job_title = $request->job_title;
                $items['job_title'] = $user->job_title ;
            }


            if ($request->exists('bio')){
                $user->bio = $request->bio;
                $items['bio'] = $user->bio ;
            }


            if ($request->exists('address')){
                $user->address = $request->address;
                $items['address'] = $user->address ;
            }



            if ($request->has('allow_notifi')){
                //dd('kk');
                $user->allow_notifi = $request->allow_notifi;
                $items['allow_notifi'] = $user->allow_notifi ;
            }

            if ($request->has('birth_day')){
                $user->birth_day = $request->birth_day;
                $items['birth_day'] = $user->birth_day ;
            }

            if ($request->has('website')){
                $user->website = $request->website;
                $items['website'] = $user->website ;
            }

            if($user->save()){
                $status = true ;
                $response = ['status' => $status ,'items' => $user];
                return response()->json($response);
            }
        }


        $status = false ;
        $response = ['status' => $status];
        return response()->json($response);
    }

    public function deviceToken(Request $request){


        $user = Auth::guard('api')->user() ;
        $user_id = $user->id ;

        if($user){
            if ($request->has('type') and $request->has('device_token') and $request->has('action')){

                $action = $request->action ;
                if( $action=='add'){

                    $device = DB::table('pn_users')->where('device_token' ,$request->device_token)->first();
                    if(!$device){
                        $device = new Device ;
                        $device->device_token = $request->device_token;
                        $device->user_id = $user_id;
                        $device->type = $request->type;
                        $device->save();


                    }else{
                        $now = Carbon::now();
                        $device = Device::where('device_token', $request->device_token )->first();
                        $device->device_token = $request->device_token;
                        $device->user_id = $user_id;
                        $device->type = $request->type;
                        $device->date = $now;
                        $device->save();
                    }

                    $status = true;
                    $response = ['status' => $status ];
                    return response()->json($response);
                }else{

                    $device = DB::table('pn_users')->where('device_token' ,$request->device_token)
                        ->where('user_id',$user_id )->first();
                    if($device ){
                        DB::table('pn_users')->where('device_token' ,$request->device_token)
                            ->where('user_id',$user_id )->delete();

                        $status = true;
                        $response = ['status' => $status ];
                        return response()->json($response);
                    }

                }


            }


        }else{
            //dd('ggg');
        }



        $status = false;
        $response = ['status' => $status ];
        return response()->json($response);

    }

}
