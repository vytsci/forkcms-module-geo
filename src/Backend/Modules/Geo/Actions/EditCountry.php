<?php

namespace Backend\Modules\Geo\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;

use Backend\Modules\Localization\Engine\Form as BackendLocalizationForm;
use Backend\Modules\Localization\Engine\Locale as BackendLocalizationLocale;

use Common\Modules\Geo\Entity\Country;

/**
 * Class EditCountry
 * @package Backend\Modules\Geo\Actions
 */
class EditCountry extends BackendBaseActionAdd
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
     * @var Country
     */
    private $country;

    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->country = new Country(array($this->getParameter('id')), BL::getActiveLanguages());

        if (!$this->country->isLoaded()) {
            $this->redirect(BackendModel::createURLForAction('Index').'&error=non-existing');
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
        $this->frm = new BackendLocalizationForm($this->locale, 'editCountry');

        $this->frm->addCheckbox('hidden', $this->country->isHidden());
        $this->frm->addText('continent_code', $this->country->getContinentCode());
        $this->frm->addText('iso_numeric', $this->country->getIsoNumeric());
        $this->frm->addText('iso_alpha_3', $this->country->getIsoAlpha3());
        $this->frm->addText('postal_code_format', $this->country->getPostalCodeFormat());
        $this->frm->addText('currency_code', $this->country->getCurrencyCode());

        while ($language = $this->locale->loopLanguage()) {
            $this->frm->addText(
                'name',
                $this->country->getLocale($language->getCode())->getName()
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

            $this->frm->getField('continent_code')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('iso_numeric')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('iso_alpha_3')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('currency_code')->isFilled(BL::err('FieldIsRequired'));

            while ($language = $this->locale->loopLanguage()) {
                $this->frm->getField('name', $language)->isFilled(BL::err('FieldIsRequired'));
                $this->locale->nextLanguage();
            }

            if ($this->frm->isCorrect()) {
                $this->country
                    ->setHidden($this->frm->getField('hidden')->isChecked())
                    ->setContinentCode($this->frm->getField('continent_code')->getValue())
                    ->setIsoNumeric($this->frm->getField('iso_numeric')->getValue())
                    ->setIsoAlpha3($this->frm->getField('iso_alpha_3')->getValue())
                    ->setPostalCodeFormat($this->frm->getField('postal_code_format')->getValue())
                    ->setCurrencyCode($this->frm->getField('currency_code')->getValue())
                    ->save();

                while ($language = $this->locale->loopLanguage()) {
                    $locale = $this->country->getLocale($language->getCode());
                    $locale
                        ->setId($this->country->getId())
                        ->setLanguage($language->getCode())
                        ->setName($this->frm->getField('name', $language)->getValue())
                        ->save();

                    $this->locale->nextLanguage();
                }

                BackendModel::triggerEvent(
                    $this->getModule(),
                    'after_edit_country',
                    array('item' => $this->country->toArray())
                );

                $this->redirect(
                    BackendModel::createURLForAction('Index').'&report=edited&var='.
                    urlencode($this->country->getLocale(BL::getWorkingLanguage())->getName()).
                    '&highlight='.$this->country->getId()
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

        $this->tpl->assign('item', $this->country->toArray());
    }
}
