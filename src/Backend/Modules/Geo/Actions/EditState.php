<?php

namespace Backend\Modules\Geo\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;

use Backend\Modules\Localization\Engine\Form as BackendLocalizationForm;
use Backend\Modules\Localization\Engine\Locale as BackendLocalizationLocale;

use Common\Modules\Geo\Entity\State;

/**
 * Class EditState
 * @package Backend\Modules\Geo\Actions
 */
class EditState extends BackendBaseActionAdd
{
    /**
     * @var BackendLocalizationLocale $locale
     */
    protected $locale;

    /**
     * The form instance
     *
     * @var BackendLocalizationForm
     */
    protected $frm;

    /**
     * @var State
     */
    private $state;

    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->state = new State(array($this->getParameter('id')), BL::getActiveLanguages());

        if (!$this->state->isLoaded()) {
            $this->redirect(BackendModel::createURLForAction('Cities').'&error=non-existing');
        }

        $this->locale = new BackendLocalizationLocale();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    /**
     * Load the form
     */
    private function loadForm()
    {
        $this->frm = new BackendLocalizationForm($this->locale, 'editState');

        $this->frm->addText('lat', $this->state->getLat());
        $this->frm->addText('lng', $this->state->getLng());
        $this->frm->addText('fcode', $this->state->getFcode());

        while ($language = $this->locale->loopLanguage()) {
            $this->frm->addText(
                'name',
                $this->state->getLocale($language->getCode())->getName()
            );
            $this->locale->nextLanguage();
        }
    }

    /**
     * Validate the form
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();

            $this->frm->getField('lat')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('lng')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('fcode')->isFilled(BL::err('FieldIsRequired'));

            while ($language = $this->locale->loopLanguage()) {
                $this->frm->getField('name', $language)->isFilled(BL::err('FieldIsRequired'));
                $this->locale->nextLanguage();
            }

            if ($this->frm->isCorrect()) {
                $this->state
                    ->setLat($this->frm->getField('lat')->getValue())
                    ->setLng($this->frm->getField('lng')->getValue())
                    ->setFcode($this->frm->getField('fcode')->getValue())
                    ->save();

                while ($language = $this->locale->loopLanguage()) {
                    $locale = $this->state->getLocale($language->getCode());
                    $locale
                        ->setId($this->state->getId())
                        ->setLanguage($language->getCode())
                        ->setName($this->frm->getField('name', $language)->getValue())
                        ->save();

                    $this->locale->nextLanguage();
                }

                BackendModel::triggerEvent(
                    $this->getModule(),
                    'after_edit_state',
                    array('item' => $this->state->toArray())
                );

                $this->redirect(
                    BackendModel::createURLForAction('States').'&report=edited&var='.
                    urlencode($this->state->getLocale(BL::getWorkingLanguage())->getName()).
                    '&highlight='.$this->state->getId()
                );
            }
        }
    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        parent::parse();

        $this->locale->parse($this->tpl);

        $this->tpl->assign('item', $this->state->toArray());
    }
}
