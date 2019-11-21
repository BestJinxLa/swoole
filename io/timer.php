<?php
/**
 * swoole 的毫秒级定时器
 * swoole_timer_tick //每隔2000ms触发一次
 * swoole_timer_after //3000ms后执行此函数
 * swoole_timer_clear // 清除此定时器，参数为定时器ID
 * @auth le118
 * date 2019/11/21
 */

$http = new swoole_http_server("0.0.0.0", 1993);

$http->set([
    'worker_num' => 1,
//    'task_worker_num' => 1,
]);

$http->on('request', function ($request, $response) {
//    print_r($request);
//    if($request->fd == 1){
//          每隔2000ms触发一次
//        swoole_timer_tick(2000,function ($timer_id){
//            echo "2s timer_id:{$timer_id} \n";
//        });
//    }
    //3000ms后执行此函数
    swoole_timer_after(3000, function () {
        echo "after 3000ms.\n";
    });

//    $response->header("Content-Type", "text/html; charset=utf-8");
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});


$http->start();

