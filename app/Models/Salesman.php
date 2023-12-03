<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Salesman extends Model
{
    use HasUuids;
    protected $primaryKey = 'uuid';
    protected $dateFormat = 'Y-m-d H:i:s.v';
    protected $fillable = [
        'first_name',
        'last_name',
        'prosight_id',
        'email',
        'phone',
        'gender_id',
        'marital_status_id',
    ];

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function titles(): BelongsToMany
    {
        return $this->belongsToMany(Title::class, 'salesman_title', 'salesman_id');
    }
}
