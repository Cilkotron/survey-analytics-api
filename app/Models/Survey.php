<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'target_responses',
        'questions',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'questions' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'target_responses' => 'integer',
    ];

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
