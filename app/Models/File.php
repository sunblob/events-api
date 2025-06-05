<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class File extends Model
{
    protected $fillable = ['filename', 'path', 'mimetype', 'page_id', 'is_editor_only'];

    protected $casts = [
        'is_editor_only' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
