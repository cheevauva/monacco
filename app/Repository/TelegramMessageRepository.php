<?php

declare(strict_types=1);

namespace App\Repository;

use Telegram\Bot\Objects\Update;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\DTO\TelegramMessageDTO;

class TelegramMessageRepository
{

    /**
     * @return TelegramMessageDTO[]
     */
    public function getMessages(?int $updateId = null): array
    {
        $telegramMessages = [];
        $updates = Telegram::getUpdates([
            'offset' => $updateId
        ]);

        foreach ($updates as $update) {
            $update = $this->asUpdate($update);

            if (empty($update->message)) {
                continue;
            }

            $telegramMessage = new TelegramMessageDTO();
            $telegramMessage->updateId = $update->updateId;
            $telegramMessage->messageId = $update->message->messageId;
            $telegramMessage->chatId = $update->message->chat->get('id');
            $telegramMessage->text = $update->message->text;
            $telegramMessage->hasDocument = !empty($update->message->document);
            $telegramMessage->isJsonDocument = !empty($update->message->document) && $update->message->document->mimeType === 'application/json';
            $telegramMessage->documentFileId = $update->message?->document?->fileId;

            $telegramMessages[] = $telegramMessage;
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

    private function getContentsByFilePath(string $filePath): string
    {
        return
            file_get_contents(sprintf(
                'https://api.telegram.org/file/bot%s/%s',
                Telegram::bot()->getAccessToken(),
                $filePath
            ));
    }
}