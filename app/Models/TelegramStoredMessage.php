<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $update_id
 * @property string $message_id
 * @property string $chat_id
 * @property array $cheque
 * @property string $reply_message_id
 */

class TelegramStoredMessage extends Model
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