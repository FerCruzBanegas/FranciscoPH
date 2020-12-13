<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $hidden = ['deleted_at'];

    protected $fillable = [
        'name', 'phone', 'ci', 'email', 'address',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
