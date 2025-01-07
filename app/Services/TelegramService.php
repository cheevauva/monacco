<?php

declare(strict_types=1);

namespace App\Services;

use Telegram\Bot\Objects\Update;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Exceptions\TelegramResponseException;
use App\DTO\TelegramMessageDTO;

class TelegramService
{

    /**
     * @return TelegramMessageDTO[]
     */
    public function getUpdates(): array
    {
        $telegramMessages = [];
        $updates = Telegram::getUpdates();

        foreach ($updates as $update) {
            $update = $this->asUpdate($update);
            
            if (empty($update->message->messageId)) {
                continue;
            }

            $telegramMessage = $telegramMessages[] = new TelegramMessageDTO();
            $telegramMessage->updateId = $update->updateId;
            $telegramMessage->messageId = $update->message->messageId;
            $telegramMessage->chatId = $update->message->chat->get('id');
            $telegramMessage->text = $update->message->text;

            if (empty($update->message->document)) {
                $telegramMessage->parseHandlerMessage = 'Не прикреплён документ';
                continue;
            }

            if ($update->message->document->mimeType !== 'application/json') {
                $telegramMessage->parseHandlerMessage = 'Документ должен иметь тип json';
                continue;
            }

            $file = $this->getContentsByFilePath($this->getFilePathByFileId($update->message->document->fileId));
            $data = json_decode($file, true);

            $telegramMessage->parsedData = $data;
            $telegramMessage->parseHandlerMessage = 'Чек принят системой';
        }

        return $telegramMessages;
    }

    private function asUpdate(Update $update): Update
    {
        return $update;
    }

    private function getFilePathByFileId(string $fileId): string
    {
        $file = Telegram::getFile([
            'file_id' => $fileId,
        ]);

        return $file['file_path'];
    }

    public function deleteMessage(string $chatId, $messageId): int
    {
        try {
            Telegram::deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $messageId
            ]);

            return 0;
        } catch (\Throwable $ex) {
            return Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => sprintf('%s (%s): %s', $ex->getFile(), $ex->getLine(), $ex->getMessage()),
                ])->messageId;
        }
    }

    public function sendMessage(string $message, int|string $chatId, int|string $replayToMessageId): int
    {
        try {
            return Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $message,
                    'reply_to_message_id' => $replayToMessageId
                ])->messageId;
        } catch (\Throwable $ex) {
            if ($ex instanceof TelegramResponseException && str_contains($ex->getMessage(), 'message to be replied not found')) {
                return 0;
            }

            return Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => sprintf('%s (%s): %s', $ex->getFile(), $ex->getLine(), $ex->getMessage()),
                ])->messageId;
        }
    }

    private function getContentsByFilePath(string $filePath): string
    {
        return file_get_contents(sprintf('https://api.telegram.org/file/bot%s/%s', Telegram::bot()->getAccessToken(), $filePath));
    }
}