<?php
/**
 * description: WebSocket协议是基于TCP的网络协议。它实现了浏览器与服务器全双工（full-duplex）
 *              通信——允许服务器主动发送信息给客户端。
 * 1.为什么要用websocket
 * http的通信只能由客户端发起（1s一次的轮询，浪费资源）
 * 2.websocket的特点
 * 建立在TCP协议之上
 * 性能开销小通信高效
 * 客户端可以与任意服务器通信
 * 协议标识符ws wss（类比http https）
 * 持久化网络通信协议（长链接）
 */

$ws = new Swoole\WebSocket\Server('0.0.0.0', 9911);

/*不配置这个，也可用http_server服务启动*/
//$ws->set([
//    'document_root' => '/www/wwwroot/le.gek6.cn/demo/data',
//    'enable_static_handler' => true,
//]);

$ws->on('open', 'onOpen');
function onOpen($ws, $request)
{
    print_r($request->fd);
}
//监听ws消息事件
$ws->on('message', 'onMessage');
function onMessage($ws, $frame)
{
    echo "receive from {$frame->fd}:{$frame->data},opCode:{$frame->opcode},finish:{$frame->finish} \n";
    $ws->push($frame->fd, "push-success:{$frame->data}", 1, 1);
}

$ws->on('close', function ($server, $fd) {
    echo "client-{$fd} closed\n";
});

$ws->start();