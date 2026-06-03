<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'survey_member_id',
        'answers',
        'duration_seconds',
        'completion_status',
        'incentive_paid',
        'completed_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'duration_seconds' => 'integer',
        'incentive_paid' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(SurveyMember::class, 'survey_member_id');
    }

    public function scopeCompleted($query)
    {
        return $query->where('completion_status', 'completed');
    }
}