<?php
// 学习下创建子进程
$pid = pcntl_fork();
if ($pid == -1) {
    die('创建子进程失败');
} else if ($pid) {
    echo "我是父进程" . PHP_EOL;
    pcntl_wait($status);
} else {
    echo "我是子进程" . PHP_EOL;
}

