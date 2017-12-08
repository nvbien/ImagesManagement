<?php

namespace Nvbien\ImagesManagement\Classes;
/**
 * Created by PhpStorm.
 * User: biennguyen
 * Date: 11/10/2017
 * Time: 3:36 PM
 */
class ImageManagement
{
    function uploadImages(){
        $response = [];
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
        return ($response);
    }

}