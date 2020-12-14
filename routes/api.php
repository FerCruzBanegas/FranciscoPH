<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
const key = 'MySecretKeyForEncryptionAndDecry'; // 32 chars
const iv = 'ivcodespanchoapp'; // 16 chars
const method = 'aes-256-cbc';

Route::get('test', function() {
    
    // return openssl_encrypt('G2CSW8xs0LZbMNPSpxtce5VRuPhmBK0R85OaSePn.jpg|5uBMIAr5a7Yg69frNWEL7vkwzy8p3q9oaHKYbmAN.mp3', method, key, 0, iv);
    return openssl_decrypt('sDR5Mf3RR0mWnUuqLp1gv25ofoP35z4i2S3MRcrMmjODt5nH3o+WW1qEmUCyWmvsXNw2TEHcfYd8kmapzpPmLQ2f4XGg9gmiZAtkYl+L6JNDM4mINFVJ+smOwAJQ4z0M', method, key, 0, iv);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
