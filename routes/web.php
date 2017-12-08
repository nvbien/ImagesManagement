<?php

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
    dd(ImagesManagement::uploadImages());
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
})->name('files');