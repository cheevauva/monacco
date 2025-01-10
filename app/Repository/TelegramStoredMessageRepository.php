<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\TelegramStoredMessage;

class TelegramStoredMessageRepository
{

    public function getLatest(): ?TelegramStoredMessage
    {
        return
                (new TelegramStoredMessage)
                ->newQuery()
                ->latest('update_id')
                ->first()
        ;
    }

    public function newMessage(): TelegramStoredMessage
    {
        return new TelegramStoredMessage;
    }

    public function findOneByMessageIdAndChatId($mesageId, $chatId): ?TelegramStoredMessage
    {
        return
                (new TelegramStoredMessage)
                ->newQuery()
                ->where('message_id', $mesageId)
                ->where('chat_id', $chatId)
                ->first()
        ;
    }

    public function save(TelegramStoredMessage $telegramMessage): void
    {
        if (is_array($telegramMessage->cheque)) {
            $telegramMessage->cheque = json_encode($telegramMessage->cheque, JSON_UNESCAPED_UNICODE);
        }

        $telegramMessage->save();
    }
}