<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    protected $fillable = ['filename', 'path', 'mimetype', 'page_id'];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
