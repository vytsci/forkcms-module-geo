<?php

namespace Common\Modules\Geo\Entity;

use Common\Modules\Localization\Engine\Entity;
use Common\Modules\Geo\Engine\Model;

/**
 * Class City
 * @package Common\Modules\Geo\Entity
 */
class City extends Entity
{

    protected $_table = Model::TBL_CITIES;

    protected $_query = Model::QRY_ENTITY_CITY;

    protected $_locale = '\\Common\\Modules\\Geo\\Entity\\CityLocale';

    protected $_columns = array(
        'state_id',
        'lat',
        'lng',
        'fcode',
    );

    protected $stateId;

    protected $lat;

    protected $lng;

    protected $fcode;

    /**
     * @var State
     */
    protected $state;

    public function getStateId()
    {
        return $this->stateId;
    }

    public function setStateId($stateId)
    {
        $this->stateId = $stateId;

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

    public function getState()
    {
        if ($this->state === null) {
            $this->state = new State(array($this->getStateId()), $this->getLanguages());
        }

        return $this->state;
    }
}
