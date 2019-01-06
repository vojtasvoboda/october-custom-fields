<?php namespace Site\User\Components;

use Cms\Classes\ComponentBase;
use Input;
use October\Rain\Support\Str;
use Site\User\Models\Field;
use Site\User\Models\User;

class Form extends ComponentBase
{
    public $fields;

    public function componentDetails()
    {
        return [
            'name' => 'Registration Form',
            'description' => 'Registration Form',
        ];
    }

    public function onRun()
    {
        $this->fields = Field::isEnabled()->orderBy('sort_order')->get();
    }

    public function onRegister()
    {
        // get data
        $data = Input::all();

        // create user
        $user = new User();
        $user->fill($data);

        // add random password
        $user->password = Str::random(6);

        // save
        $user->save();
    }
}
