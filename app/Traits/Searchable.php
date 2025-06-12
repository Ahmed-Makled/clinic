<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Searchable
 *
 * This trait provides search functionality for Eloquent models.
 */
trait Searchable
{
    /**
     * Scope a query to search by specific fields.
     *
     * @param Builder $query
     * @param string $term
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $searchableFields = $this->getSearchableFields();

        if (empty($searchableFields)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term, $searchableFields) {
            foreach ($searchableFields as $field) {
                $query->orWhere($field, 'LIKE', "%{$term}%");
            }
        });
    }

    /**
     * Get the searchable fields for the model.
     *
     * @return array
     */
    public function getSearchableFields(): array
    {
        // If model has a $searchable property, use it
        if (property_exists($this, 'searchable')) {
            return $this->searchable;
        }

        // Otherwise, return empty array or default fields
        return [];
    }

    /**
     * Scope a query to search by multiple fields with advanced options.
     *
     * @param Builder $query
     * @param array $searchTerms
     * @return Builder
     */
    public function scopeAdvancedSearch(Builder $query, array $searchTerms): Builder
    {
        if (empty($searchTerms)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($searchTerms) {
            foreach ($searchTerms as $column => $value) {
                // Skip empty values
                if (empty($value)) {
                    continue;
                }

                // If we have a direct column match
                if (is_string($column) && !is_array($value)) {
                    $query->where($column, 'LIKE', "%{$value}%");
                }
                // If we have a specific operator
                elseif (is_string($column) && is_array($value) && isset($value['value'])) {
                    $operator = $value['operator'] ?? 'LIKE';

                    if ($operator === 'LIKE') {
                        $query->where($column, $operator, "%{$value['value']}%");
                    } else {
                        $query->where($column, $operator, $value['value']);
                    }
                }
            }
        });
    }

    /**
     * Perform a fuzzy search on specified fields.
     *
     * @param Builder $query
     * @param string $term
     * @return Builder
     */
    public function scopeFuzzySearch(Builder $query, string $term): Builder
    {
        $searchableFields = $this->getSearchableFields();

        if (empty($searchableFields) || empty($term)) {
            return $query;
        }

        // Split search term into words
        $words = explode(' ', $term);

        return $query->where(function (Builder $query) use ($words, $searchableFields) {
            foreach ($searchableFields as $field) {
                foreach ($words as $word) {
                    if (strlen($word) >= 2) { // Only search for words with at least 2 characters
                        $query->orWhere($field, 'LIKE', "%{$word}%");
                    }
                }
            }
        });
    }
}
