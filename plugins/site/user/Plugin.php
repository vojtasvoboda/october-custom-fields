<?php namespace Site\User;

use Backend;
use Site\User\Components\Form;
use System\Classes\PluginBase;

/**
 * User Plugin Information File
 */
class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            Form::class => 'form',
        ];
    }
}
