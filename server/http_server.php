<?php
/**
 * @auth le118
 * date 2019/11/14
 */
$http = new swoole_http_server("0.0.0.0", 1993);

$http->set([
    'document_root' => '/www/wwwroot/le.gek6.cn/demo/data',
    'enable_static_handler' => true,
]);

$http->on('request', function ($request, $response) {
//    var_dump($request->get,$request->post,$request->cookie);
    $response->header("Content-Type", "text/html; charset=utf-8");
//    $response->cookie('le118','age_26',time()-1800);
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});

$http->start();