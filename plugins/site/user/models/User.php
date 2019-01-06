<?php namespace Site\User\Models;

use Model;
use October\Rain\Database\Traits\Hashable as HashableTrait;
use October\Rain\Database\Traits\SoftDelete as SoftDeletingTrait;
use October\Rain\Database\Traits\Validation as ValidationTrait;
use October\Rain\Support\Str;

/**
 * User Model
 */
class User extends Model
{
    const IDENT_LENGTH = 6;

    use HashableTrait;

    use SoftDeletingTrait;

    use ValidationTrait;

    /** @var string The database table used by the model. */
    public $table = 'site_user_users';

    /** @var array $rules Model rules. */
    public $rules = [
        'name' => 'required|max:255',
        'ident' => 'required|unique:site_user_users|size:' . self::IDENT_LENGTH,
        'password' => 'required:create|between:4,255',
        'enabled' => 'boolean',
    ];

    /** @var array $dates Fields whose are converted to Carbon object. */
    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    /** @var array List of attribute names which should be hashed using the Bcrypt hashing algorithm. */
    protected $hashable = ['password'];

    /** @var array $belongsToMany Belongs to many relations. */
    public $belongsToMany = [
        'groups' => [
            Group::class,
            'table' => 'site_user_users_groups',
            'scope' => 'isEnabled',
            'timestamps' => true,
        ],
        'fields' => [
            Field::class,
            'table' => 'site_user_users_fields',
            'scope' => 'isEnabled',
            'timestamps' => true,
            'pivot' => ['value'],
        ],
    ];

    /**
     * Before validate user model.
     */
    public function beforeValidate()
    {
        // before create
        if ($this->id === null) {
            $this->ident = $this->getUniqueIdent();
        }

        // create password when not set
        if (empty($this->password)) {
            $this->password = Str::random(6);
        }
    }

    /**
     * @return string|null
     */
    public function getUniqueIdent()
    {
        $count = 0;
        do {
            $ident = Str::random(self::IDENT_LENGTH);
            $exists = self::where('ident', $ident)->first();

        } while ($exists !== null && ++$count < 1000);

        return $count < 1000 ? $ident : null;
    }

    /**
     * Load custom fields as array.
     *
     * @return array
     */
    public function getFieldsAttribute()
    {
        return $this->fields()->get()->lists('pivot.value', 'ident');
    }
}
