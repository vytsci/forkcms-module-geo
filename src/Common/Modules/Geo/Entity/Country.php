<?php

namespace Common\Modules\Geo\Entity;

use Common\Modules\Localization\Engine\Entity;
use Common\Modules\Geo\Engine\Model;

/**
 * Class Country
 * @package Common\Modules\Geo\Entity
 */
class Country extends Entity
{

    protected $_table = Model::TBL_COUNTRIES;

    protected $_query = Model::QRY_ENTITY_COUNTRY;

    protected $_locale = '\\Common\\Modules\\Geo\\Entity\\CountryLocale';

    protected $_columns = array(
        'hidden',
        'continent_code',
        'iso_numeric',
        'iso_alpha_3',
        'postal_code_format',
        'currency_code',
    );

    protected $_relations = array(
        'states',
    );

    protected $hidden = true;

    protected $continentCode;

    protected $isoNumeric;

    protected $isoAlpha3;

    protected $postalCodeFormat;

    protected $currencyCode;

    protected $states;

    public function isHidden()
    {
        return $this->hidden;
    }

    public function setHidden($hidden)
    {
        $this->hidden = (bool)$hidden;

        return $this;
    }

    public function getContinentCode()
    {
        return $this->continentCode;
    }

    public function setContinentCode($continentCode)
    {
        $this->continentCode = $continentCode;

        return $this;
    }

    public function getIsoNumeric()
    {
        return $this->isoNumeric;
    }

    public function setIsoNumeric($isoNumeric)
    {
        $this->isoNumeric = $isoNumeric;

        return $this;
    }

    public function getIsoAlpha3()
    {
        return $this->isoAlpha3;
    }

    public function setIsoAlpha3($isoAlpha3)
    {
        $this->isoAlpha3 = $isoAlpha3;

        return $this;
    }

    public function getPostalCodeFormat()
    {
        return $this->postalCodeFormat;
    }

    public function setPostalCodeFormat($postalCodeFormat)
    {
        $this->postalCodeFormat = $postalCodeFormat;

        return $this;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function countStates()
    {
        return count($this->states);
    }

    public function getStates()
    {
        return $this->states;
    }

    public function setStates($states)
    {
        $this->states = $states;

        return $this;
    }

    public function addCity(State $state)
    {
        $this->states[$state->getId()] = $state;

        return $this;
    }
}
