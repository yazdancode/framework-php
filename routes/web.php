<?php
use App\Core\Routing\Route;
use App\Middleware\BlockFirefox;
use App\Middleware\BlockIE;

// ثبت route ها
Route::get('/', 'HomeController@index');
Route::get('/todo/list', 'TodoController@list', [BlockFirefox::class, BlockIE::class]);
Route::get('/todo/add', 'TodoController@add');
Route::get('/todo/remove', 'TodoController@remove');


Route::get('/archive', 'ArchiveController@index');
Route::get('/archive/articles', 'ArchiveController@articles');
Route::get('/archive/products', 'ArchiveController@products');

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