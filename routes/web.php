<?php


$router->get('/', function () use ($router) {
    // return $router->app->version();
    return '';
});


$router->get('v1/user/get', 'UserController@get');
$router->get('v1/route/live', 'RouteController@fetch');
$router->get('v1/track/point', 'RouteController@getCheckpoint');
$router->get('v1/check/not-patrol-yet', 'ScheduleController@check');
$router->get('v1/get/progress-patrol','RouteController@getRouteData');

$router->get('generate','UserController@generate');
$router->get('check','UserController@getShift');




$router->post('v1/report/post','ReportController@save');

$router->post('v1/user/login', 'UserController@auth');
$router->post('v1/user/logout', 'UserController@logout');
$router->post('v1/route/collect', 'RouteController@save');
$router->post('v1/route/checkpoint', 'RouteController@checkpoint');
$router->post('v1/send/data', 'RouteController@getData');
$router->post('v1/upload', 'RouteController@uploadFile');
$router->post('v1/panic', 'RouteController@panic');

