<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class ServiceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function($service){
                return [
                    'id' => $service->id,
                    'date' => $service->date,
                    'qr' => $service->qr,
                    'customer' => $service->customer->name,
                    'created_at' => Carbon::parse($service->created_at)->format('Y-m-d'),
                    'updated_at' => Carbon::parse($service->updated_at)->format('Y-m-d'),
                ];
            }),
        ];
    }
}
