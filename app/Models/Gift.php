<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'attributes',
        'lottie',
        'original_details',
        'sender_user_id',
        'recipient_user_id'
    ];

    protected $casts = [
        'attributes' => 'array',
        'original_details' => 'array'
    ];

    public function sender()
    {
        return $this->belongsTo(TgUser::class, 'sender_user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(TgUser::class, 'recipient_user_id');
    }
}
