<?php
//一个简单的socket服务端 with select模型

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

$clients = [$s];
$recvs = [];

// 接收消息
do {
    $read = $clients;
    $ret = @socket_select($read, $write = null, $expect = null);
    if ($ret === false) {
        break;
    }


    foreach ($read as $k => $client) {
        if ($client === $s) {
            $conn = socket_accept($s);
            if (!$conn) {
                echo "阻塞失败";
                break;
            }

            $clients[] = $conn;

            socket_getpeername($conn, $addr, $port);
            echo "连接的客户端:" . $addr . $port . PHP_EOL;

            socket_getsockname($conn, $addr, $port);
            echo "服务端地址:" . $addr . $port . PHP_EOL;

            echo "总连接数:" . (count($clients) - 1) . "个" . PHP_EOL;
        } else {
            if (!isset($recvs[$k])) {
                $recvs[$k] = '';
            }

            $buffer = socket_read($client, 1024);
            if ($buffer === false || $buffer === '') {
                echo "客户端关闭连接" . PHP_EOL;
                unset($client[array_search($client, $clients)]);
                socket_close($client);
            }

            $pos = strpos($buffer, PHP_EOL);
            if ($pos === false) {
                $recvs[$k] .= $buffer;
            } else {
                $recvs[$k] .= trim(substr($buffer,0,$pos +1));

                if ($recvs[$k] == 'quit') {
                    echo "客户端关闭close" . PHP_EOL;
                    unset($clients[array_search($client, $clients)]);
                    socket_close($client);
                    break;
                }

                echo "收到消息:" . $recvs[$k] . PHP_EOL;
                socket_write($client, $recvs[$k] . PHP_EOL);

                $recvs[$k] = '';
            }
        }
    }

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