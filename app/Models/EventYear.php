<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EventYear extends Model
{
    use HasFactory;

    protected $fillable = ['year', 'title', 'description', 'is_active'];

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
