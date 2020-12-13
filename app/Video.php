<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Video extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $hidden = ['deleted_at'];

    protected $fillable = [
        'path', 'service_id',
    ];

    protected $appends = ['video_url'];

    public function getVideoUrlAttribute()
    {
        return Storage::url($this->path);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
