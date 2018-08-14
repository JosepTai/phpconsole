<?php
/**
 * Loads all the app helper functions
 */

if (!function_exists('settings')) {
    function settings($asObject = false) {
        $settings = \App\Setting::first();

        if ($asObject) {
            return json_decode($settings->settings);
        }

        return json_encode($settings->settings);
    }
}

if (!function_exists('isConnected')) {
    function isConnected() {
        $connected = @fsockopen("www.github.com", 80);
        //website, port  (try 80 or 443)
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }
}

if (!function_exists('defineRoutes')) {
    function defineRoutes(array $routes) {
        $allRoutes = [];
        foreach ($routes as $route) {
            if ($route['type'] == 'get') {
                \Illuminate\Support\Facades\Route::get($route['url'],
                    $route['callback']);
                $allRoutes[] .= $route;
            }
            \Illuminate\Support\Facades\Storage::disk('base')->append('routes/defined.txt', json_encode($allRoutes));
        }


    }
}

if (!function_exists('includeDefinedRoutes')) {
    function includeDefinedRoutes(bool $include = true) {
        if ($include) {
            include_once(base_path('routes/defined.php'));
        }
    }
}