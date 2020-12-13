<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\UppyUploaderTrait;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    use UppyUploaderTrait;

    // public function store(Request $request)
    // {
    //     if ($request->file) {

    //         $file = $this->moveFileToPath($request->file);

    //         $service = Image::create([
	   //          'path' => $file,
	   //          'service_id' => 1
	   //      ]);

    //         return response([
    //             'path' => Storage::url($service->path)
    //         ], 201);

    //     }
    //     return response([], 400);
    // }
}
