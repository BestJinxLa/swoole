<?php
/**
 * 开启多个子进程，循环new swoole_process
 * Created by le118
 * Author: le118
 * Date: 2019/12/9
 * Time: 9:59
 */

$urls = [
//    'https://www.php.net/manual/zh/book.curl.php',
//    'https://bbs.hupu.com/31152191.html',
    10, 2, 3, 2, 1
];
$i = 0;
while ($i < 5) {
    $process = new swoole_process(function (swoole_process $worker) use ($urls, $i) {
        $content = getRequestDemo($urls[$i]);
        echo $content . PHP_EOL;
    }, true);
    $pid = $process->start();
    $works[$pid] = $process;
    $i++;
}
//$works = array_reverse($works);
foreach ($works as $pid=>$process) {
    echo $pid.'=>'.$process->read();
    $process->wait(); //回收结束运行的子进程。
}

/*
 * 未回收子进程
 *  [root@VM_0_14_centos ~]# pstree -p 26588
    php(26588)─┬─php(26590)
               ├─php(26591)
               ├─php(26592)
               ├─php(26593)
               └─php(26594)
    [root@VM_0_14_centos ~]# pstree -p 29569
    php(29569)───php(29571)
 * */

/**
 * 模拟耗时操作
 * @param $s
 * @return mixed
 */
function getRequestDemo($s){
    sleep($s);
    return $s;
}

/**
 * Curl send get request, support HTTPS protocol
 * @param string $url The request url
 * @param string $refer The request refer
 * @param int $timeout The timeout seconds
 * @return mixed
 */
function getRequest($url, $refer = "", $timeout = 10)
{
    $ssl = stripos($url, 'https://') === 0 ? true : false;
    $curlObj = curl_init();
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_AUTOREFERER => 1,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)',
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
        CURLOPT_HTTPHEADER => ['Expect:'],
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
    ];
    if ($refer) {
        $options[CURLOPT_REFERER] = $refer;
    }
    if ($ssl) {
        //support https
        $options[CURLOPT_SSL_VERIFYHOST] = false;
        $options[CURLOPT_SSL_VERIFYPEER] = false;
    }
    curl_setopt_array($curlObj, $options);
    $returnData = curl_exec($curlObj);
    if (curl_errno($curlObj)) {
        //error message
        $returnData = curl_error($curlObj);
    }
    curl_close($curlObj);
    return $returnData;
}