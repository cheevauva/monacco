<?php

use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Keyboard\Keyboard;

Route::get('/', function () {
    echo '<pre>';
    $updates = Telegram::getUpdates([
        'offset' => '645930685',
        'limit' => 1000,
    ]);

    foreach ($updates as $update) {
        assert($update instanceof Update);
        var_dump($update->message);
        if ($update->message->document) {
            print_r($update->message->document);
            $file = Telegram::getFile([
                'file_id' => $update->message->document->fileId,
            ]);

            $file = file_get_contents(sprintf('https://api.telegram.org/file/bot%s/%s', Telegram::bot()->getAccessToken(), $file['file_path']));
            $data = json_decode($file, true);

            if ($data) {
                Telegram::sendMessage([
                    'chat_id' => $update->getChat()->get('id'),
                    'text' => 'Чек принят',
                    'reply_to_message_id' => $update->message->message_id
                ]);
            }
        }
    }
});
