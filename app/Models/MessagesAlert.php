<?php

namespace App\Models;

use App\Events\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;
use Exception;

class MessagesAlert extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'message',
        'is_marked',
        'message_type',
        'record_id'
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($data) {
            try {
                broadcast(new Message($data));
            } catch (Exception $e) {
                Log::error('Failed to connect to Pusher: ' . $e->getMessage());
            }
        });
    }
}
