<?php namespace Site\User\Models;

use Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Traits\SoftDelete as SoftDeletingTrait;
use October\Rain\Database\Traits\Validation as ValidationTrait;

/**
 * Group Model
 */
class Group extends Model
{
    use SoftDeletingTrait;

    use ValidationTrait;

    /** @var string $table The database table used by the model. */
    public $table = 'site_user_groups';

    /** @var array $rules Model rules. */
    public $rules = [
        'name' => 'required|max:255',
        'ident' => 'required|unique:site_user_groups',
        'enabled' => 'boolean',
    ];

    /** @var array $dates Fields whose are converted to Carbon object. */
    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Scope for fetching only enabled groups.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeIsEnabled(Builder $query)
    {
        return $query->where('enabled', true);
    }
}
