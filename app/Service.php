<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $hidden = ['deleted_at'];

    protected $fillable = [
        'date', 'qr', 'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function image()
    {
        return $this->hasOne(Image::class);
    }

    public function audio()
    {
        return $this->hasOne(Audio::class);
    }

    public function video()
    {
        return $this->hasOne(Video::class);
    }
}
