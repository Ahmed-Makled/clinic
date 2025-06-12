<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasStatus
 *
 * This trait provides status management functionality for Eloquent models.
 */
trait HasStatus
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootHasStatus()
    {
        // You can add any boot-time behavior here
    }

    /**
     * Get the status column name.
     *
     * @return string
     */
    public function getStatusColumn(): string
    {
        return defined('static::STATUS_COLUMN') ? static::STATUS_COLUMN : 'status';
    }

    /**
     * Get the available statuses.
     *
     * @return array
     */
    public static function getStatuses(): array
    {
        return defined('static::STATUSES') ? static::STATUSES : [];
    }

    /**
     * Check if the model has a specific status.
     *
     * @param string $status
     * @return bool
     */
    public function hasStatus(string $status): bool
    {
        return $this->{$this->getStatusColumn()} === $status;
    }

    /**
     * Update the model's status.
     *
     * @param string $status
     * @return bool
     */
    public function updateStatus(string $status): bool
    {
        if (!in_array($status, static::getStatuses())) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        return $this->update([$this->getStatusColumn() => $status]);
    }

    /**
     * Scope a query to only include models with a specific status.
     *
     * @param Builder $query
     * @param string $status
     * @return Builder
     */
    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where($this->getStatusColumn(), $status);
    }

    /**
     * Scope a query to only include models with one of the given statuses.
     *
     * @param Builder $query
     * @param array $statuses
     * @return Builder
     */
    public function scopeWithStatuses(Builder $query, array $statuses): Builder
    {
        return $query->whereIn($this->getStatusColumn(), $statuses);
    }

    /**
     * Scope a query to only include active models.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        // Assuming 'active' is a common status across models
        // Override this in specific models if needed
        return $query->where($this->getStatusColumn(), 'active');
    }

    /**
     * Get the status label.
     *
     * @return string|null
     */
    public function getStatusLabelAttribute(): ?string
    {
        $labels = defined('static::STATUS_LABELS') ? static::STATUS_LABELS : [];
        $status = $this->{$this->getStatusColumn()};

        return $labels[$status] ?? $status;
    }

    /**
     * Get the status color for UI.
     *
     * @return string|null
     */
    public function getStatusColorAttribute(): ?string
    {
        $colors = defined('static::STATUS_COLORS') ? static::STATUS_COLORS : [];
        $status = $this->{$this->getStatusColumn()};

        return $colors[$status] ?? 'gray';
    }
}
