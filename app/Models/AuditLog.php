<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who performed this action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record an audit log entry
     */
    public static function record(
        string $action,
        string $description,
        $model = null,
        $oldValues = null,
        $newValues = null
    ): self {
        return self::create([
            'user_id' => Auth::id() ?? 1,
            'action' => $action,
            'model_type' => $model ? class_basename($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'created_at' => now(),
        ]);
    }

    /**
     * Get action badge color
     */
    public function getActionBadgeAttribute(): array
    {
        return match ($this->action) {
            'login', 'logout' => ['color' => 'blue', 'icon' => 'bi-door-open'],
            'created' => ['color' => 'green', 'icon' => 'bi-plus-circle'],
            'updated' => ['color' => 'warning', 'icon' => 'bi-pencil'],
            'deleted' => ['color' => 'danger', 'icon' => 'bi-trash'],
            'opened_shift', 'closed_shift' => ['color' => 'purple', 'icon' => 'bi-clock'],
            'recorded_sale' => ['color' => 'teal', 'icon' => 'bi-receipt'],
            'recorded_delivery' => ['color' => 'info', 'icon' => 'bi-truck'],
            'reconciled_shift' => ['color' => 'dark', 'icon' => 'bi-calculator'],
            default => ['color' => 'secondary', 'icon' => 'bi-info-circle'],
        };
    }
}
