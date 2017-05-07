<?php

namespace Backend\Modules\Geo\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;

use Backend\Modules\Localization\Engine\Form as BackendLocalizationForm;
use Backend\Modules\Localization\Engine\Locale as BackendLocalizationLocale;

use Backend\Modules\Geo\Engine\Model as BackendGeoModel;

/**
 * Class AddCity
 * @package Backend\Modules\Geo\Actions
 */
class AddState extends BackendBaseActionAdd
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
     * @var
     */
    private $countryCode;

    /**
     * @var
     */
    private $parentId;

    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->locale = new BackendLocalizationLocale();

        $this->countryCode = $this->getParameter('country_code');
        $this->parentId = $this->getParameter('parent_id');

        if (empty($this->countryCode)) {
            $this->redirect(BackendModel::createURLForAction('Index').'&error=non-existing');
        }

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
        $this->frm = new BackendLocalizationForm($this->locale, 'addChild');

        $this->frm->addText('id');
        $this->frm->addText('fcode');
        $this->frm->addCheckbox('has_children');

        while ($language = $this->locale->loopLanguage()) {
            $this->frm->addText('name');
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

            $this->frm->getField('id')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('fcode')->isFilled(BL::err('FieldIsRequired'));

            while ($language = $this->locale->loopLanguage()) {
                $this->frm->getField('name', $language)->isFilled(BL::err('FieldIsRequired'));
                $this->locale->nextLanguage();
            }

            if ($this->frm->isCorrect()) {
                $item = array(
                    'id' => $this->frm->getField('id')->getValue(),
                    'parent_id' => isset($this->parentId) ? $this->parentId : null,
                    'country_code' => $this->countryCode,
                    'fcode' => $this->frm->getField('fcode')->getValue(),
                    'has_children' => $this->frm->getField('has_children')->isChecked(),
                );

                while ($language = $this->locale->loopLanguage()) {
                    $itemLocaleName = $this->frm->getField('name', $language)->getValue();

                    $item['locale'][$language->getCode()] = array(
                        'language' => $language->getCode(),
                        'name' => $itemLocaleName,
                    );

                    $this->locale->nextLanguage();
                }

                BackendGeoModel::insertChild($item);

                BackendModel::triggerEvent(
                    $this->getModule(),
                    'after_add_child',
                    array('item' => $item)
                );

                $this->redirect(
                    BackendModel::createURLForAction('Children').'&report=added&var='.
                    urlencode($item['locale'][BL::getWorkingLanguage()]['name']).
                    '&highlight='.$item['id']
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
    }
}
