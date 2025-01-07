<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $telegramService = new App\Services\TelegramMessageService();
    $telegramService->importFromTelegram();
});
Route::get('/test', function () {
    $telegramService = new App\Services\TelegramService;

    foreach ($telegramService->getUpdates() as $telegramMessage) {
        $telegramService->sendMessage($telegramMessage->parseHandlerMessage ?? 'Неизвестный результат', $telegramMessage->chatId, $telegramMessage->messageId);
    }
});
