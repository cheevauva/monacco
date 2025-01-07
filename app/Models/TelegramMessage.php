<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramMessage extends Model
{

    protected $table = 'telegram_message';
    public $incrementing = true;
    protected $fillable = [
        'update_id',
        'message_id',
        'chat_id',
        'cheque',
    ];
}