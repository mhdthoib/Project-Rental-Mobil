<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    Use HasFactory,SoftDeletes;

    protected $fillable =[
        'name',
        'slug',
        'brand_id',
        'type_id',
        'photos',
        'seat',
        'features',
        'price',
        'star',
        'review',
    ];
    protected $casts=[
        'photos' => 'array',
    ];
        //Get first photo fron photos
        public function getThumbnailAttribute()
        {
            if ($this->photos) {
                return Storage::url(json_decode($this->photos)[0]);
            }
            return 'https://via.placeholder.com/800x600';

        }
    public function brand()
    {
    return $this->belongsTo(Brand::class);
    }
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}