<?php
use App\Core\Routing\Route;

// ثبت route ها
Route::get('/', 'HomeController@index');
Route::get('/archive', 'ArchiveController@index');

Route::add(['get','post', 'put'], '/a', static function (){
    echo 'welcome';
});

Route::get( '/b', static function () {
    echo 'save ok';
});
$response = [
    'data' => [
        'routes' => Route::routes()
    ]
];
http_response_code(200);