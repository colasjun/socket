<?php
//一个简单的socket服务端

// 创建socket
//AF_INET IPv4
//SOCK_STREAM TCP
// SOL_TCP TCP ???为什么要指定2次 第二个参数和第三个参数啥区别呢？？
$s = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

// 建立连接
if (socket_connect($s, '127.0.0.1', 8999)) {
    $send_msg = "我喜欢你，服务端，我是客户端";

    if (socket_write($s, $send_msg, strlen($send_msg))) {
        echo "我发送成功了" . PHP_EOL;
        while ($return_msg = socket_read($s, 1024)) {
            echo "服务端回我消息了:" . $return_msg . PHP_EOL;
        }
    } else {
        echo "socket客户端发送失败" . PHP_EOL;
    }
}