<?php
/**
 * @auth le118
 * date 2019/11/26
 * 进程就是正在运行的程序的一个实例
 *
 * swoole进程与进程之间的通信是通过管道进行通信。
 *
 * process.php这个脚本是主进程创建了子进程 $process
 */

/**
 *
 */
$process = new Swoole\Process('call_back_demo2', false);

$pid = $process->start();

echo $pid . PHP_EOL;

Swoole\Process::wait(); //回收运行的子进程

function call_back_demo1(Swoole\Process $worker)
{
    echo 'i`m update' . PHP_EOL;
}

function call_back_demo2($worker)
{
    $worker->exec('/www/server/php/72/bin/php', array(__DIR__ . '/../server/http_server.php'));
}

/**
 * [root@VM_0_14_centos src]# pstree -p 22831
 *                  master      manager
    php(22831)───php(22832)─┬─php(22833)─┬─php(22837)
    │            ├─php(22838)
    │            ├─php(22839)
    │            └─php(22840)
    ├─{php}(22834)
    └─{php}(22835)
 */