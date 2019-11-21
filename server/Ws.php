<?php
/**
 * Ws 优化基础类库
 * @auth le118
 * date 2019/11/18
 */

/**
 * task使用
 * 设置$ws->set(['worker_num'=>4,'task_worker_num'=>4])
 * 1.$ws->task($data);
 * 2.onTask
 * 3.onFinish
 */
class Ws
{
    const HOST = "0.0.0.0";
    const PORT = 9911;
    protected $ws = null;

    public function __construct()
    {
        $this->ws = new Swoole\WebSocket\Server(self::HOST, self::PORT);

        $this->ws->set([
            'worker_num' => 2,
            'task_worker_num' => 2,
        ]);

        /*注意对象的回调函数的写法*/
        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('task', [$this, 'onTask']);
        $this->ws->on('finish', [$this, 'onFinish']);
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
//        var_dump($request->fd);
        if ($request->fd == 1) {
            swoole_timer_tick(2000, function ($timer_id) {
                echo "time_id:{$timer_id} \n";
            });
        }
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
        /*任务内容*/
        $data = [
            'task_name' => 01,
            'task_content' => 'first task',
        ];
//        $ws->task($data);
        swoole_timer_after(5000, function () use ($ws, $frame) {
            $ws->push($frame->fd, 'server say: push-success:' . date('Y-m-d H:i:s'));
        });
        $ws->push($frame->fd, 'server-push-success:' . date('Y-m-d H:i:s'));
    }

    /**
     * 监听任务事件
     * 执行完之后会触发onFinish事件
     * @param $ws
     * @param $task_id，任务ID，由swoole扩展内自动生成，用于区分不同的任务
     * $task_id和$src_worker_id组合起来才是全局唯一的，不同的worker进程投递的任务ID可能会有相同
     * @param $src_worker_id，来自于哪个worker进程
     * @param $data , 任务的内容
     */
    public function onTask($ws, $task_id, $src_worker_i, $data)
    {
        print_r($data);
        sleep(10);
        return "task finished";
    }

    /**
     * 监听ws 任务完成事件
     * @param $ws
     * @param $task_id
     * @param $data，这里的data是onTask返回的数据
     */
    public function onFinish($ws, $task_id, $data)
    {
        echo "taskID:{$task_id} \n";
        echo "finish-data-success:{$data} \n";
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