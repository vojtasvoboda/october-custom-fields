<?php namespace Site\User\Models;

use Model;
use October\Rain\Database\Traits\Hashable as HashableTrait;
use October\Rain\Database\Traits\Purgeable as PurgeableTrait;
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

    use PurgeableTrait;

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

    /** @var array $fillable Fillable fields. */
    public $fillable = ['name', 'ident', 'password', 'surname', 'email', 'phone', 'fields_array'];

    /** @var array List of attribute names which should be hashed using the Bcrypt hashing algorithm. */
    protected $hashable = ['password'];

    /** @var array $jsonable Which fields are array, so we need to convert them to json. */
    protected $jsonable = ['fields_array'];

    /** @var array $purgeable Purge attributes from data set. */
    protected $purgeable = ['fields_array'];

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
     * Before create/update user model, save/update all custom fields.
     */
    public function afterSave()
    {
        // custom form fields to save
        $userFieldsArray = $this->getOriginalPurgeValue('fields_array');
        $userFields = collect(json_decode($userFieldsArray, true));

        // load all original custom fields objects
        $allowedFields = Field::isEnabled()->get();

        // save user fields
        $userFields->each(function ($value, $key) use ($allowedFields) {
            // try to find existing record
            $field = $this->fields()->where('ident', $key)->first();

            // if found, update
            if ($field !== null) {
                $field->pivot->value = $value;
                $field->pivot->save();
                return;
            }

            // don't save empty values
            if (empty($value)) {
                return;
            }

            // find original field by key
            $field = $allowedFields->where('ident', $key)->first();

            // skip non-valid fields
            if ($field === null) {
                return;
            }

            // save for the user
            $this->fields()->add($field, [
                'value' => $value,
            ]);
        });
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
    public function getCustomFieldsAttribute()
    {
        return $this->fields()->get()->lists('pivot.value', 'ident');
    }
}
