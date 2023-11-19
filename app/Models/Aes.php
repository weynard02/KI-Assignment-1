<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aes extends Model
{
    use HasFactory;

    protected $fillable = ['fullname', 'id_card', 'document', 'video', 'user_id', 'fullname_key', 'fullname_iv', 'id_card_key', 'id_card_iv', 'document_key', 'document_iv', 'video_key', 'video_iv'];

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
