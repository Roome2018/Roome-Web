<?php
namespace App;
use Laravel\Passport\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;


/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $image
 * @property string $nationality
 * @property string $remember_token
*/
class User extends Authenticatable
{
    use HasApiTokens,Notifiable;
    protected $fillable = ['name', 'email','mobile', 'password', 'image', 'nationality', 'remember_token'];
    protected $hidden = ['password', 'remember_token'];
    protected $appends = array('urlimage');


    public function getUrlImageAttribute()
    {
        if ($this->image) {
            return asset('public/uploads/'.$this->image);
        } else {
            return null;
        }
    }
    
    
    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
    
    
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }
    
    
    

    public function sendPasswordResetNotification($token)
    {
       $this->notify(new ResetPassword($token));
    }


    public static function validateCreateAccount(Request $request,$user_id = null)
    {

        $rules = [
            'email' => Rule::unique('users')->ignore( $user_id, 'id') ,
            'mobile' => Rule::unique('users')->ignore( $user_id, 'id'),
            'name' => 'required',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $arr = array();
            $errors = [];
            $messages = $validator->errors()->toArray();
            foreach ($messages as $key => $row) {
                $errors['fieldname'] = $key;
                $errors['message'] = $row[0];
                $arr[] = $errors;
            }
            $response = ['status' => false, 'errors' => $arr];
            return $response;
        }
        $response = ['status' => true, 'items' => $request->all()];
        return $response;
    }


    public function findForPassport($identifier) {
        return $this->orWhere('email', $identifier)->orWhere('mobile', $identifier)->first();
    }
}
