<?php
/**
 * @auth le118
 * date 2019/11/21
 */

swoole_async_readfile(__DIR__."/1.txt", function($filename, $content) {
    echo "$filename: $content".PHP_EOL;
});