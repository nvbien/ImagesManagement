<?php

use Illuminate\Http\Request;
use App\File;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/file', function (Request $request) {
    $arr = [];
    foreach ($_FILES as $k => $_file) {
        if ($_file['tmp_name'] && $path =  \Illuminate\Support\Facades\Storage::putFile('photos', new \Illuminate\Http\File($_file['tmp_name']))) {
            $file = new File();
            $file->name = $_file['name'];
            $file->size =$_file['size'];
            $file->path = env('APP_URL').'files/'.$path;
            $file->server_path = $path;
            $file->save();
            $file->url = route('files', $file->id);
            array_push($arr, $file);
        }
    }
    return response()->json($arr);
});