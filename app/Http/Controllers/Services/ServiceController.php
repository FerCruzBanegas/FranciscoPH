<?php

namespace App\Http\Controllers\Services;

use App\Image;
use App\Audio;
use App\Video;
use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\UppyUploaderTrait;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Service\ServiceResource;

class ServiceController extends Controller
{
    use UppyUploaderTrait;

    public function filter (Request $request)
    {
        $query = Service::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $services = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        $services->load('customer');

        return $services;
    }

    public function show ($service)
    {
        return Service::findOrFail($service);
    }

    public function detail (Service $service)
    {
        return new ServiceResource($service);
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'customer' => 'required|integer',
        ]);

        $service = Service::create([
            'date' => date('Y-m-d'),
            'customer_id' => $request->customer,
        ]);

        if ($request->image) {
            $file = $this->moveFileToPath($request->image);

            $image = Image::create([
                'path' => $file,
                'service_id' => $service->id
            ]);
        }

        if ($request->src) {
            $file = $this->moveFileToPath($request->src);
            
            if ($request->type == 'audio') {
                Audio::create([
                    'path' => $file,
                    'service_id' => $service->id
                ]);
            } else {
                Video::create([
                    'path' => $file,
                    'service_id' => $service->id
                ]);
            }
        }

        return $request;
    }

    public function update (Request $request, Service $service)
    {
        $this->validate($request, [
            'name' => 'required|string|max:64',
            'phone' => 'required|string|max:32',
            'ci' => 'required|string|max:16',
            'email' => 'nullable|max:128|email|unique:customers',
            'address' => 'nullable|string|max:128'
        ]);

        $service->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'ci' => $request->ci,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return $service;
    }

    public function destroy ($service)
    {
        return Service::destroy($service);
    }

    public function count ()
    {
        return Service::count();
    }
}
