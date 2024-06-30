<?php

namespace Hive;

use Exception;

class AIController
{
    private Session $session;

    public function __construct() {
        $this->session = new Session();
    }

    /**
     * @throws Exception
     */
    public function handlePost(): void
    {
        $game = $this->session->getFromSession('game');
        $url = 'http://127.0.0.1:5000/';
        $data = [
            'board' => $game->board,
            'hand' => $game->hand,
            'move_number' => $game->move_number ?? 0
        ];


        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            $error = error_get_last();
            file_put_contents('debug.log', "Error: " . print_r($error, true) . PHP_EOL, FILE_APPEND);
        } else {
            $decodedResult = json_decode($result, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                file_put_contents('debug.log', "Decoded result: " . print_r($decodedResult, true) . PHP_EOL, FILE_APPEND);
                $route = $decodedResult[0];

                $controller = match ($route) {
                    'move' => new MoveController(),
                    'pass' => new PassController(),
                    'play' => new PlayController(),
                };
                $controller->handlePost($decodedResult[1], $decodedResult[2]);

            } else {
                file_put_contents('debug.log', "JSON decode error: " . json_last_error_msg() . PHP_EOL, FILE_APPEND);
                file_put_contents('debug.log', "Raw result: " . $result . PHP_EOL, FILE_APPEND);
            }
        }

        App::redirect();
    }
}