<?php
// 一个基于浏览器的server
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, '127.0.0.1', '9999');

socket_listen($socket, 1);

while (1) {
    // 程序主动询问 内存空间 这个资源准备好了没有？？ 同步阻塞
    $client = socket_accept($socket);
    if ($client) {
        // 说明请求到了
        $msg = socket_read($client, 1024);
        echo "收到消息:" . $msg . PHP_EOL;

        $send_msg = "HTTP/1.1 200 OK" . PHP_EOL . "Content-TYpe:text/html;charset=utf-8" . PHP_EOL . PHP_EOL;
        socket_write($client, $send_msg);
        socket_write($client, "哈哈哈哈，我收到你消息了!");
        socket_close($client);
    }
}

socket_close($socket);