<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['month', 'year', 'amount'];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
