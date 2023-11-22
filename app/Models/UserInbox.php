<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInbox extends Model
{
    use HasFactory;

    protected $fillable = ['main_user_id', 'client_user_id', 'is_acc', 'type'];

    public function mainUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'main_user_id');
    }

    public function clientUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }
}
