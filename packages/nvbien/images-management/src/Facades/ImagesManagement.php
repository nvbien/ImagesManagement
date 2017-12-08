<?php
/**
 * Created by PhpStorm.
 * User: biennguyen
 * Date: 11/10/2017
 * Time: 3:38 PM
 */

namespace Nvbien\ImagesManagement\Facades;
use Illuminate\Support\Facades\Facade;
class ImagesManagement extends Facade{
    protected static function getFacadeAccessor()
    {
        return 'imageManagement';
    }
}