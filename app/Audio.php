<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Audio extends Model
{
    use SoftDeletes;
    
    protected $table = 'audios';

    protected $dates = ['deleted_at'];

    protected $hidden = ['deleted_at'];

    protected $fillable = [
        'path', 'service_id',
    ];

    protected $appends = ['audio_url'];

    public function getAudioUrlAttribute()
    {
        return Storage::url($this->path);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
