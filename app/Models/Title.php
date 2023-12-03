<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    const TYPE_BEFORE = 'before_name';
    const TYPE_AFTER = 'after_name';
}
