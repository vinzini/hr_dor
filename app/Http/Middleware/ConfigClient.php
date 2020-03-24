<?php

namespace App\Http\Middleware;

use Closure;

class ConfigClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $config = [
            config('general.client_id') => config('general.client_id_val'),
            config('general.client_secret') => config('general.client_secret_val'),
        ];   

        if(request()->has('refresh_token')){
            $config[config('general.grant_type')] = config('general.grant_type_refresh');
        }else{
            $config[config('general.grant_type')] = config('general.grant_type_password');
        }
        request()->request->add($config); //add request
        
        return $next($request);
    }
}
