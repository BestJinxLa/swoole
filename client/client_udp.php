<?php
/**
 * @auth le118
 * date 2019/11/14
 */
$client = new swoole_client(SWOOLE_SOCK_UDP);

if (!$client->connect('127.0.0.1', 9527, 0.5)) {
    die('connect failed');
}

fwrite(STDOUT, '请输入你像对服务器说的话:');
$msg = fgets(STDIN);
if (!$client->send($msg)) {
    die('send failed');
}

//从服务器接收数据
$data = $client->recv();
if (!$data)
{
    die("recv failed.");
}
echo $data;
$client->close();