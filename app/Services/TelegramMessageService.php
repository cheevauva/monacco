<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\TelegramService;
use App\Repository\TelegramStoredMessageRepository;
use App\Repository\TelegramMessageRepository;
use App\Repository\TelegramFileRepository;

class TelegramMessageService
{

    public function __construct(
        protected TelegramStoredMessageRepository $telegramStoredMessageRepo,
        protected TelegramService $telegramService,
        protected TelegramMessageRepository $telegramMessageRepo,
        protected TelegramFileRepository $telegramFileRepo
    )
    {
        
    }

    public function importFromTelegram(): void
    {
        $latestStoredMessage = $this->telegramStoredMessageRepo->getLatest();
        
        foreach ($this->telegramMessageRepo->getMessages(intval($latestStoredMessage->update_id ?? null)) as $message) {
            $storedMessage = $this->telegramStoredMessageRepo->findOneByMessageIdAndChatId($message->messageId, $message->chatId);

            if (!empty($storedMessage->reply_message_id)) {
                continue;
            }


            $replyMessage = 'Неизвестный результат';

            if (!$message->hasDocument) {
                $replyMessage = 'Не прикреплён документ';
            }

            if ($message->hasDocument && !$message->isJsonDocument) {
                $replyMessage = 'Документ должен иметь тип json';
            }

            $parsedData = null;

            if ($message->hasDocument && $message->isJsonDocument && $message->documentFileId) {
                $parsedData = $this->telegramFileRepo->getContentAsDecodedJsonByFileId($message->documentFileId);
                $replyMessage = 'Документ принят в обработку';
            }

            $storedMessage ??= $this->telegramStoredMessageRepo->newMessage();
            $storedMessage->update_id = $message->updateId;
            $storedMessage->chat_id = $message->chatId;
            $storedMessage->message_id = $message->messageId;
            $storedMessage->cheque = $parsedData;
            $storedMessage->reply_message_id = -1;

            $this->telegramStoredMessageRepo->save($storedMessage);

            $storedMessage->reply_message_id = $this->telegramService->sendMessage(
                $replyMessage,
                $message->chatId,
                $message->messageId
            );

            $this->telegramStoredMessageRepo->save($storedMessage);
        }
    }
}