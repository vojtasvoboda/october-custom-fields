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

    /** @var string The database table used by the model. */
    public $table = 'site_user_groups';

    /** @var array Rules */
    public $rules = [
        'name' => 'required|max:255',
        'ident' => 'required|unique:site_user_groups',
        'enabled' => 'boolean',
    ];

    /** @var array $dates */
    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Scope for fetching only enabled groups.
     *
     * @param $query
     * @return Builder
     */
    public function scopeIsEnabled($query)
    {
        return $query->where('enabled', true);
    }
}
