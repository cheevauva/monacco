<?php

declare(strict_types=1);

namespace App\Repository;

use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramFileRepository
{

    public function getContentByFileId(string $fileId): string
    {
        $file = Telegram::getFile([
            'file_id' => $fileId,
        ]);

        return $this->getContentsByFilePath($file['file_path']);
    }

    public function getContentAsDecodedJsonByFileId(string $fileId): array
    {
        return json_decode($this->getContentByFileId($fileId), true);
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