<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gender extends Model
{
    public function salesmen(): HasMany
    {
        return $this->HasMany(Salesman::class);
    }

    public function maritalStatusNames(): HasMany
    {
        return $this->HasMany(MaritalStatusName::class);
    }
}
