<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\TelegramService;
use App\Models\TelegramMessage;

class TelegramMessageService
{

    public function importFromTelegram(): void
    {
        $telegramService = new TelegramService;

        foreach ($telegramService->getUpdates() as $message) {
            $telegramMessage = (new TelegramMessage)->newQuery()->firstOrCreate(...[
                [
                    'message_id' => $message->messageId,
                    'chat_id' => $message->chatId,
                ],
                [
                    'update_id' => $message->updateId,
                    'mesage_id' => $message->messageId,
                    'chat_id' => $message->chatId,
                    'cheque' => json_encode($message->parsedData, JSON_UNESCAPED_UNICODE),
                ]
            ]);

            if (!$telegramMessage->reply_message_id) {
                $telegramMessage->reply_message_id = $telegramService->sendMessage($message->parseHandlerMessage ?? 'Неизвестный результат', $message->chatId, $message->messageId);
                $telegramMessage->save();
            }
        }
    }
}