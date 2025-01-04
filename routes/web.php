<?php

use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Keyboard\Keyboard;

Route::get('/', function () {
    //dd(Telegram::getMe());
    $updates = Telegram::getUpdates();
    foreach ($updates as $update) {
        assert($update instanceof Update);
        print_r($update);
    }


    if (0) {
        $response = Telegram::sendMessage([
            'chat_id' => $update->getChat()->get('id'),
            'text' => 'Hello World',
            'reply_markup' => Keyboard::make()
                ->setResizeKeyboard(true)
                ->setOneTimeKeyboard(true)
                ->row([
                    Keyboard::button('1'),
                    Keyboard::button('2'),
                    Keyboard::button('3'),
                ])
        ]);
        $messageId = $response->getMessageId();
    }
});
