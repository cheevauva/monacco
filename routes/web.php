<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

Route::get('/', [MainController::class, 'index']);
Route::get('/test', function () {
    $telegramService = new App\Services\TelegramService;

    foreach ($telegramService->getUpdates() as $telegramMessage) {
        $telegramService->sendMessage($telegramMessage->parseHandlerMessage ?? 'Неизвестный результат', $telegramMessage->chatId, $telegramMessage->messageId);
    }
});
