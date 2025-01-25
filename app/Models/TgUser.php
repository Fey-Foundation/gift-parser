<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TgUser extends Model
{
    protected $fillable = [
        'telegram_username',
        'name',
        'telegram_id'
    ];

    public function sentGifts()
    {
        return $this->hasMany(Gift::class, 'sender_user_id');
    }

    public function receivedGifts()
    {
        return $this->hasMany(Gift::class, 'recipient_user_id');
    }
}
