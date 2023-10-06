<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Des extends Model
{
    use HasFactory;

    protected $fillable = ['fullname', 'id_card', 'document', 'video', 'user_id', 'key', 'iv'];

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
