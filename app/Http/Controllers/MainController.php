<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\TelegramMessageService;

class MainController
{

    public function __construct(
        public TelegramMessageService $telegramMessageService
    )
    {
        
    }

    public function index(): void
    {
        $this->telegramMessageService->importFromTelegram();
    }
}