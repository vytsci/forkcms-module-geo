<?php

namespace Common\Modules\Geo\Entity;

use Common\Modules\Localization\Engine\Entity;
use Common\Modules\Geo\Engine\Model;

/**
 * Class State
 * @package Common\Modules\Geo\Entity
 */
class State extends Entity
{

    protected $_table = Model::TBL_STATES;

    protected $_query = Model::QRY_ENTITY_STATE;

    protected $_locale = '\\Common\\Modules\\Geo\\Entity\\StateLocale';

    protected $_columns = array(
        'country_id',
        'lat',
        'lng',
        'fcode',
    );

    protected $_relations = array(
        'cities',
    );

    protected $countryId;

    protected $lat;

    protected $lng;

    protected $fcode;

    protected $country;

    protected $cities;

    public function getCountryId()
    {
        return $this->countryId;
    }

    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    public function getFcode()
    {
        return $this->fcode;
    }

    public function setFcode($fcode)
    {
        $this->fcode = $fcode;

        return $this;
    }

    public function getCountry()
    {
        if ($this->country === null) {
            $this->country = new Country(array($this->getCountryId()), $this->getLanguages());
        }

        return $this->country;
    }

    public function countCities()
    {
        return count($this->cities);
    }

    public function getCities()
    {
        return $this->cities;
    }

    public function setCities($cities)
    {
        $this->cities = $cities;

        return $this;
    }

    public function addCity(City $city)
    {
        $this->cities[$city->getId()] = $city;

        return $this;
    }
}
