<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Issue extends BaseModel
{
    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }
}
