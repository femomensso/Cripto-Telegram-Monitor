<?php

use GuzzleHttp\Client;

class MercadoBitcoin {

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function ticker()
    {
        return $this->call('ticker');
    }

    public function call($method)
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'https://www.mercadobitcoin.net/api/' . $this->config . '/' . $method);
            $response = $response->getBody();
            $response = json_decode($response);
            return $response;
        } 
        catch (\Throwable $th) {
            return $th;
        }
    }
}


class TelegramBot {

    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.telegram.org/bot' . $_ENV['telegram_bot'] . '/'
        ]);
    }

    public function sendMessage($msg) 
    {
        $data = [
            'chat_id' => $_ENV['chat_id'],
            'text' => $msg
        ];

        $response = $this->client->request('GET', 'sendMessage?' . http_build_query($data));
        return $response;   
    }
}



?>