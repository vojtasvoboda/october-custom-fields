<?php namespace Site\User\Controllers;

use Backend\Widgets\Form;
use BackendMenu;
use Backend\Classes\Controller;
use Site\User\Models\Field;

/**
 * Users Back-end Controller
 */
class Users extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Site.User', 'user', 'users');
    }

    /**
     * Override rendering backend form and add custom fields.
     *
     * @param int|null $recordId
     * @param string|null $context
     */
    public function update($recordId = null, $context = null)
    {
        parent::update($recordId, $context);

        /** @var Form $form */
        $form = $this->formGetWidget();

        // add all possible custom fields
        Field::isEnabled()->each(function ($field) use ($form) {
            $key = sprintf('fields[%s]', $field->ident);
            $form->addTabFields([
                $key => [
                    'tab' => 'Custom fields',
                    'label' => $field->name,
                ],
            ]);
        });
    }
}
