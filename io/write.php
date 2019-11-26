<?php
/**
 * @auth le118
 * date 2019/11/26
 */

//返回bool
$file_content = date('Y-m-d H:i:s') . PHP_EOL;

/*第四个参数追加写入*/
////每次4M
//$res = swoole_async_writefile('test.log', $file_content, function ($filename) {
//    echo "success ok." . PHP_EOL;
//}, FILE_APPEND);
$res = swoole_async_write('test.log', $file_content, -1, function ($filename) {
    echo "success ok." . PHP_EOL;
});

