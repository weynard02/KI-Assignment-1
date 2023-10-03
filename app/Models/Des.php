<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Des extends Model
{
    use HasFactory;

    protected $fillable = ['fullname', 'id_card', 'document', 'video'];
}
