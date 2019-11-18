<?php
/**
 * Ws 优化基础类库
 * @auth le118
 * date 2019/11/18
 */

class Ws
{
    const HOST = "0.0.0.0";
    const PORT = 9911;
    protected $ws = null;

    public function __construct()
    {
        $this->ws = new Swoole\WebSocket\Server(self::HOST, self::PORT);
        /*注意对象的回调函数的写法*/
        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('close', [$this, 'onClose']);
        $this->ws->start();
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws, $request)
    {
        var_dump($request->fd);
    }

    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     * $frame->fd，客户端的socket id，使用$server->push推送数据时需要用到
     * $frame->data，数据内容，可以是文本内容也可以是二进制数据，可以通过opcode的值来判断
     * $frame->opcode，WebSocket的OpCode类型，可以参考WebSocket协议标准文档
     * $frame->finish， 表示数据帧是否完整，一个WebSocket请求可能会分成多个数据帧进行发送（底层已经实现了自动合并数据帧，现在不用担心接收到的数据帧不完整）
     */
    public function onMessage($ws, $frame)
    {
        echo "ser-push-message:{$frame->data} \n";
        $ws->push($frame->fd, 'server-push:' . date('Y-m-d H:i:s'));
    }

    /**
     * 监听ws的close事件
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd)
    {
        echo "client {$fd} closed \n";
    }
}

$ws = new Ws();