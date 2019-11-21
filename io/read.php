<?php
/**
 * swoole异步读取文件
 * @auth le118
 * date 2019/11/21
 */

/*
 * 先读取文件返回true/false
 * 再执行 start
 * 最后执行 闭包函数
 * */
//$res = Swoole\Async::readfile(__DIR__."/1.txt", function($filename, $content) {
////$res = swoole_async_readfile(__DIR__."/1.txt", function($filename, $content) {
//    echo "$filename: $content".PHP_EOL;
//});

//swoole_async_readfile
$res = Swoole\Async::read(__DIR__."/1.txt", function($filename, $content) {
    /*会输出文本长度/2次*/
    echo "$filename: $content".PHP_EOL;
}, 2);



var_dump($res);

sleep(2);
echo "start".PHP_EOL;