<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        $qr = null;

        if (!is_null($this->video)) {
            $qr = $this->video->video_url;
        } else {
            $qr = $this->image->image_url.'|'.$this->audio->audio_url;
        }
        return [
            'id' => $this->id,
            'date' => $this->date,
            'customer' => $this->customer->name,
            'image' => $this->image,
            'audio' => $this->audio,
            'video' => $this->video,
            'qr' => $qr,
            'created' => Carbon::parse($this->created_at)->format('Y-m-d')
        ];
    }
}
