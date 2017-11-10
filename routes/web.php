<?php

use \Intervention\Image\Facades\Image;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/', function () {
    $client = new GuzzleHttp\Client();
    $response ='';
    foreach ($_FILES as $k =>  $file){
        if($file['tmp_name']){
            $response = $client->request('POST', 'http://im.local/api/file', [
                'multipart' => [
                    [
                        'name'     => $file['name'],
                        'type'     => $file['type'],
                        'contents' => fopen($file['tmp_name'] ,'r'),
                        'size' => $file['size'],
                    ]
                ]
            ]);
        }
    }
    dd(json_decode($response->getBody()));
});
Route::post('/curl', function () {
    $response ='';
    foreach ($_FILES as $k =>  $file){
        if($file['tmp_name']){
            if (function_exists('curl_file_create')) { // php 5.5+
            $cFile = curl_file_create($file['tmp_name']);
        } else { //
            $cFile = '@' . realpath($file['tmp_name']);
        }
            $post = array('extra_info' => '123456','file_contents'=> $cFile);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,'http://im.local/api/file');
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $response= curl_exec ($ch);
            curl_close ($ch);
        }
    }
    dd(json_decode($response));
});
Route::get('/files/{id}/{w?}/{h?}', function (\Symfony\Component\HttpFoundation\Request $request, $id , $w ='', $h ='') {
        $file = \App\File::find($id);
        if($file)
        {
            $w = $w !=''? $w : $request->get('w','');
            $h = $h !=''? $h : $request->get('h','');
            if($w =='' && $h == '')
            {
                return Image::make( storage_path() .'/app/'.$file->server_path)->response();
            }
            else{
                return Image::make( storage_path() .'/app/'.$file->server_path)->resize($w, $h)->response();
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'File not found!',
        ]);
});