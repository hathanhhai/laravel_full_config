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

// Route::get('/', ['uses' => 'Auth\LoginController@loginView', 'as' => 'loginView']);
// Route::post('/login', ['uses' => 'Auth\LoginController@login', 'as' => 'login']);


Route::any('/{controller?}/{method?}', function ($controller = null, $method = null) {
    $controller = $controller ? $controller : 'home';
    $method = isset($method) ? $method : 'index';
    $app_controller = config('router.router');
    if (isset($app_controller[$controller])) {
        $namespace = config('router.namespace');
        if ($controller == 'home') {
            $namespace = "App\Http\Controllers";
        }
        $admin_class_name = "{$namespace}{$app_controller[ $controller ]}";
        if (class_exists($admin_class_name)) {
            $admin_class = new $admin_class_name();
            if (method_exists($admin_class, $method)) {
                $controller_levels = explode("\\", $app_controller[$controller]);
                $count_controller_level = count($controller_levels);
                $role_name = '';
                if ($count_controller_level > 0) {
                    $role_name = $controller_levels[$count_controller_level - 1];
                }
                if ($method == 'doAction' && isset($_REQUEST['action'])) {
                    $send_method = ucfirst(strtolower(request()->method()));
                    $class_string = \App\Helper\Helper::convertObjectName($_REQUEST['action']);
                    $name_with_method = '_action' . $send_method . $class_string;
                    $name_with_any = '_actionAny' . $class_string;
                }
                return $admin_class->{$method}();
            }
        }
    }
    if (request()->ajax()) {
        return response()->json(['message' => 'Not Found ', 'action' => $method], 404);
    }
    return response()->json(['message' => 'Not Found ', 'action' => $method], 404);
});





