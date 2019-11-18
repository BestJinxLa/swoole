<?php

$server = new swoole_server('127.0.0.1', 9527, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

$server->set(array(
    'worker_num' => 4,
    'max_request' => 500
));

//监听数据接收事件
$server->on('Packet', function ($server, $data, $clientInfo) {
    $server->sendto($clientInfo['address'], $clientInfo['port'], "Server ".$data);
    var_dump($clientInfo);
});

//启动服务器
$server->start();