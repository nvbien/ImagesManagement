<?php


namespace Nvbien\ImagesManagement;

use Illuminate\Support\ServiceProvider;

class ImageManagementServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('imageManagement', 'Nvbien\ImagesManagement\Classes\ImageManagement' );
    }
}
