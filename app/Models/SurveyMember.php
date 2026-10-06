<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyMember extends Model
{
    use HasFactory;

    protected $table = 'survey_members';

    protected $fillable = [
        'email',
        'name',
        'country',
        'age_group',
        'gender',
        'total_responses',
        'total_earnings',
        'last_active_at',
    ];

    protected $casts = [
        'total_responses' => 'integer',
        'total_earnings' => 'decimal:2',
        'last_active_at' => 'datetime',
    ];

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function scopeActive(mixed $query, $days = 30)
    {
        return $query->where('last_active_at', '>=', now()->subDays($days));
    }
}
