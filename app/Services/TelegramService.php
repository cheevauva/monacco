<?php

declare(strict_types=1);

namespace App\Services;

use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Exceptions\TelegramResponseException;

class TelegramService
{

    public function deleteMessage(string $chatId, $messageId): int
    {
        try {
            Telegram::deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $messageId
            ]);

            return 0;
        } catch (\Throwable $ex) {
            return
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => sprintf('%s (%s): %s', $ex->getFile(), $ex->getLine(), $ex->getMessage()),
                ])->messageId;
        }
    }

    public function sendMessage(
        string $message,
        int|string $chatId,
        int|string $replayToMessageId
    ): int
    {
        try {
            return
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $message,
                    'reply_to_message_id' => $replayToMessageId
                ])->messageId;
        } catch (\Throwable $ex) {
            if ($ex instanceof TelegramResponseException && str_contains($ex->getMessage(), 'message to be replied not found')) {
                return 0;
            }

            return
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => sprintf('%s (%s): %s', $ex->getFile(), $ex->getLine(), $ex->getMessage()),
                ])->messageId;
        }
    }
}