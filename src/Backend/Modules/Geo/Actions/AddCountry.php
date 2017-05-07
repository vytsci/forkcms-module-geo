<?php

namespace Backend\Modules\Geo\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;

use Backend\Modules\Localization\Engine\Form as BackendLocalizationForm;
use Backend\Modules\Localization\Engine\Locale as BackendLocalizationLocale;

use Backend\Modules\Geo\Engine\Model as BackendGeoModel;

use Common\Modules\Geo\Engine\Helper as CommonGeoHelper;

/**
 * Class AddCountry
 * @package Backend\Modules\Events\Actions
 */
class AddCountry extends BackendBaseActionAdd
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
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

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
        $this->frm = new BackendLocalizationForm($this->locale, 'addCountry');

        $this->frm->addText('id');
        $this->frm->addCheckbox('hidden');
        $this->frm->addText('continent_code');
        $this->frm->addText('iso_numeric');
        $this->frm->addText('iso_alpha_3');
        $this->frm->addText('postal_code_format');
        $this->frm->addText('currency_code');

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

            $this->frm->getField('code')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('geoname_id')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('continent_code')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('iso_numeric')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('iso_alpha_3')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('currency_code')->isFilled(BL::err('FieldIsRequired'));

            while ($language = $this->locale->loopLanguage()) {
                $this->frm->getField('name', $language)->isFilled(BL::err('FieldIsRequired'));
                $this->locale->nextLanguage();
            }

            if ($this->frm->isCorrect()) {
                $item = array(
                    'id' => $this->frm->getField('id')->getValue(),
                    'hidden' => $this->frm->getField('hidden')->isChecked(),
                    'continent_code' => $this->frm->getField('continent_code')->getValue(),
                    'iso_numeric' => $this->frm->getField('iso_numeric')->getValue(),
                    'iso_alpha_3' => $this->frm->getField('iso_alpha_3')->getValue(),
                    'postal_code_format' => $this->frm->getField('postal_code_format')->getValue(),
                    'currency_code' => $this->frm->getField('currency_code')->getValue(),
                );

                while ($language = $this->locale->loopLanguage()) {
                    $itemLocaleName = $this->frm->getField('name', $language)->getValue();

                    $item['locale'][$language->getCode()] = array(
                        'language' => $language->getCode(),
                        'name' => $itemLocaleName,
                    );

                    $this->locale->nextLanguage();
                }

                BackendGeoModel::insertCountry($item);

                BackendModel::triggerEvent(
                    $this->getModule(),
                    'after_add_country',
                    array('item' => $item)
                );

                $this->redirect(
                    BackendModel::createURLForAction('Index').'&report=added&var='.
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
