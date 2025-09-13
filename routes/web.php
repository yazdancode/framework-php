<?php
use App\Core\Routing\Route;

// ثبت route ها
Route::get('/null', static function() {
    echo 'null page';
});

Route::add(['get','post'], '/', static function (){
    echo 'welcome';
});

Route::add(['post'], '/saveForm', static function () {
    echo 'save ok';
});
$response = [
    'status' => 'success',
    'count' => count(Route::routes()),
    'timestamp' => date('Y-m-d H:i:s'),
    'data' => [
        'routes' => Route::routes()
    ]
];
header('Content-Type: application/json');
http_response_code(200);
try {
    echo json_encode($response, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (JsonException $e) {

}