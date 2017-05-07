<?php

namespace Common\Modules\Geo\Entity;

use Common\Modules\Localization\Engine\EntityLocale;
use Common\Modules\Geo\Engine\Model;

/**
 * Class CountryLocale
 * @package Common\Modules\Geo\Entity
 */
class CountryLocale extends EntityLocale
{

    protected $_table = Model::TBL_COUNTRIES_LOCALE;

    protected $_query = Model::QRY_ENTITY_COUNTRY_LOCALE;

    protected $_primary = array('id', 'language');

    protected $_columns = array(
        'name',
    );

    protected $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
