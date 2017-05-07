<?php

namespace Common\Modules\Geo\Entity;

use Common\Modules\Localization\Engine\EntityLocale;
use Common\Modules\Geo\Engine\Model;

/**
 * Class StateLocale
 * @package Common\Modules\Geo\Entity
 */
class StateLocale extends EntityLocale
{

    protected $_table = Model::TBL_STATES_LOCALE;

    protected $_query = Model::QRY_ENTITY_STATE_LOCALE;

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
