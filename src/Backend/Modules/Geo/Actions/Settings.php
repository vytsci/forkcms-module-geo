<?php

namespace Backend\Modules\Geo\Actions;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;

/**
 * Class Settings
 * @package Backend\Modules\Geo\Actions
 */
class Settings extends BackendBaseActionEdit
{

    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    /**
     * Loads the settings form
     */
    private function loadForm()
    {
        $this->frm = new BackendForm('settings');

        $this->frm->addText('username', $this->get('fork.settings')->get('Geo', 'username'));
    }

    /**
     * Validates the settings form
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            if ($this->frm->isCorrect()) {
                $this->get('fork.settings')->set(
                    'Geo',
                    'username',
                    $this->frm->getField('username')->getValue()
                );

                $this->redirect(BackendModel::createURLForAction('Settings').'&report=saved');
            }
        }
    }

    protected function parse()
    {
        parent::parse();
    }
}
