<?php

declare(strict_types=1);

namespace App\DTO;

class TelegramMessageDTO
{

    public int $updateId;
    public int $messageId;
    public $chatId;
    public ?string $text = null;
    public ?array $parsedData = null;
    public ?string $parseHandlerMessage = null;
}