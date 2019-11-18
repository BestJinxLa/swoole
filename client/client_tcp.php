<?php

$cli = new swoole_client(SWOOLE_SOCK_TCP);

//连接服务器
if (!$cli->connect('127.0.0.1', 9501, 0.5)) {
    die("connect failed.");
}
//向服务器发送数据
//php cli常量
fwrite(STDOUT, '请输入发送内容:');
$msg = fgets(STDIN);
if (!$cli->send($msg)) {
    die('send data failed.');
}
//从服务器接受数据
$data = $cli->recv();
echo $data;
$cli->close();

