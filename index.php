<?php

require 'vendor/autoload.php';
require 'env.php';
require 'api.php';

$loop = React\EventLoop\Factory::create();

$timer = $loop->addPeriodicTimer($_ENV['msg_time'], function () {

$telegramBot = new TelegramBot;

//$coins = ['BTC', 'ETH', 'LTC', 'XRP'];
$coins = $_ENV['coins'];
$msg = '';

foreach ($coins as $coin) {
    $infoApi = new MercadoBitcoin($coin);
    $response = $infoApi->ticker();
    $open = floatval($response->ticker->open);
    $last = floatval($response->ticker->last);

    $warning = '';

    $change = number_format((($last-$open)/$open)*100, 3) . '%';
    if ($change < -10) {
        $warning = 'ATENCAO: ';
    }

    if (empty($msg)) {
        $msg = 'Variacāo das suas criptomoedas nas últimas 24 horas: ' . "\n" . $warning . $coin . ': ' . $change . "\n";
    } else {
        $msg = $msg . $warning . $coin . ':' . $change . "\n";
    }
}

$telegramresponse = $telegramBot->sendMessage($msg);

});

$loop->addTimer(60.0, function () use ($loop, $timer) {
    $loop->cancelTimer($timer);
});


$loop->run();





?>