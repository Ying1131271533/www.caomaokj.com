<?php

namespace app\common\logic\command;

class Chat
{
    public function handle($ws, $frame)
    {
        // WebSocket会存储所有用户连接进来的fd
        foreach ($ws->connections as $fd) {
            dump($fd);
            // 需要先判断是否是正确的websocket连接，否则有可能会push失败
            if ($ws->isEstablished($fd)) {
                // 场景设定为1:1聊天
                // 和正在连接的fd进行对比，例如我fd-01和对方fd-02正在聊天
                // 判断是我，还是对方发送的消息
                if ($fd == $frame->fd) {
                    // 投递一个异步任务到 task_worker 池中。此函数是非阻塞的，执行完毕会立即返回。
                    // Worker 进程可以继续处理新的请求。
                    // 使用 Task 功能，必须先设置 task_worker_num ，并且必须设置 Server 的 onTask 和 onFinish 事件回调函数。
                    $ws->task([
                        'fd'      => $fd,
                        'messags' => "我发送的消息: {$frame->data}",
                    ]);
                    // 服务端用fd来向客户端用户：我，发送消息，返回消息是告知是我发送的消息
                    $ws->push($fd, "我发送的消息: {$frame->data}");
                } else {
                    // 服务端用fd来向客户端用户：对方，发送消息，返回消息是告知是对方发送的消息
                    $ws->push($fd, "对方发送的消息: {$frame->data}");
                    /* try {
                        $ws->push($fd, "对方发送的消息: {$frame->data}");
                    } catch (\Throwable $th) {
                        echo '无效的连接，fd：' . $fd;
                    } */
                }
            };
        }
    }
}