<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Page extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'content', 'event_year_id'];

    protected $casts = [
        'content' => 'array',
    ];

    public function eventYear(): BelongsTo
    {
        return $this->belongsTo(EventYear::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
