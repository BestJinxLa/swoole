<?php
/**
 * @auth le118
 * date 2019/11/26
 */

$redis = new swoole\redis; //swoole_redis;

/*
 * 执行顺序
 * echo start => set('key')=> echo sleeping 2s => get('name') => get('key')
 * */
$redis->connect('127.0.0.1',6379,function (swoole_redis $client, $result){
    if($result == false){
        echo 'redis connected fail.';
        return;
    }
    // set('key')
    $client->set('key','swoole',function (swoole_redis $client, $result){
        var_dump($result);
        // get('key')
        $client->keys('*ey',function (swoole_redis $client,$result){
            var_dump($result);
        });
    });
    // get('name')
    $client->get('name',function (swoole_redis $client,$result){
        echo 'sleeping 2s'.PHP_EOL;
        sleep(2);
        var_dump($result);
        $client->close(); // 虽然在这里close，但是还是会执行完所有的操作(set('key'))之后才会真正的关闭连接
    });
});

echo 'start'.PHP_EOL;