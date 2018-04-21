<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

/**
 * Class Room
 *
 * @package App
 * @property string $title
 * @property string $info
 * @property string $location
 * @property decimal $price
 * @property integer $max_tenants
 * @property integer $view_count
 * @property string $is_available
*/
class Room extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = ['title','owner', 'info', 'price', 'max_tenants', 'view_count', 'is_available', 'location_address', 'location_latitude', 'location_longitude'];
    protected $hidden = [];
    
    

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setPriceAttribute($input)
    {
        $this->attributes['price'] = $input ? $input : null;
    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setMaxTenantsAttribute($input)
    {
        $this->attributes['max_tenants'] = $input ? $input : null;
    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setViewCountAttribute($input)
    {
        $this->attributes['view_count'] = $input ? $input : null;
    }
    
    public function tenants()
    {
        return $this->belongsToMany(User::class, 'room_user');
    }
    
}
