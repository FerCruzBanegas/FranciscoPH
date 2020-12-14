<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'customer' => $this->customer->name,
            'image' => $this->image,
            'audio' => $this->audio,
            'video' => $this->video,
            'qr' => $this->qr,
            'created' => Carbon::parse($this->created_at)->format('Y-m-d')
        ];
    }
}
