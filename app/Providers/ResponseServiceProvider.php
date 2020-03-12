<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register the application's response macros.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($auth,$message, $data = []) {
            $authenticate = ($auth == true ? "true" : "false");
            return Response::json(['status' => "true", 'auth' => $authenticate,'message' => $message, 'data' => $data]);
        });

        Response::macro('fail', function ($auth,$message,$error_data=[]) {
            $authenticate = ($auth == true ? "true" : "false");            
            return Response::json(['status' => "false", 'auth' => $authenticate,'message' => $message,
                'error' => $error_data]);
        });

    }
}
    
     

