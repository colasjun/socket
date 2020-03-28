<?php
//一个简单的socket服务端

// 创建socket
//AF_INET IPv4
//SOCK_STREAM TCP
// SOL_TCP TCP ???为什么要指定2次 第二个参数和第三个参数啥区别呢？？
$s = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

// 绑定地址
// $s 打开的socket资源
// 127.0.0.1 监听的主机
// 8999 监听的端口
if (!socket_bind($s, '127.0.0.1', 8999)) {
    echo "socket绑定失败" . socket_strerror(socket_last_error()) . PHP_EOL;
}

// 监听套字节流
if (!socket_listen($s, 4)) {
    echo "socket监听失败" . socket_strerror(socket_last_error()) . PHP_EOL;
}

// 接收消息
do {
    $accept = socket_accept($s);
    if ($accept) {
        $msg = socket_read($accept, 1024, PHP_BINARY_READ);
        echo "收到客户的消息:" . $msg . PHP_EOL;

        // 发送消息给客户端
        $return_msg = "我收到你的消息了(" . $msg . ")";

        // 写入消息
        socket_write($accept, $return_msg, strlen($return_msg));
    } else {
        echo "socket读取信息失败" . socket_strerror(socket_last_error()) . PHP_EOL;
    }
    // 关闭当前socket资源符??
    socket_close($accept);
}while(true);