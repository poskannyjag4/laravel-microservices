<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'status', 'user_id', 'category_id'];

    public function category(): BelongsTo{
        return $this->belongsTo(Category::class);
    }
}
