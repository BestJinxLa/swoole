<?php

//创建Server对象，监听 127.0.0.1:9501端口
$serv = new swoole_server("127.0.0.1", 9501);

$serv->set(array(
    'worker_num' => 4,    //worker process num CPU核数的1-4倍
    'max_request' => 500,
));

//监听连接进入事件
/*
*$fd 客户端连接的唯一标识
*$reactor_id 线程id
*/
$serv->on('Connect', function ($serv, $fd, $reactor_id) {  
    echo "Client:{$reactor_id}-{$fd}-Connect.\n";
});

//监听数据接收事件
$serv->on('Receive', function ($serv, $fd, $reactor_id, $data) {
    $serv->send($fd, "Client:{$reactor_id}-Server_workerID:{$fd}-send: ".$data);
});

//监听连接关闭事件
$serv->on('Close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$serv->start(); 
