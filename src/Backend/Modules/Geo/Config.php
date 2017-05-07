<?php

namespace Backend\Modules\Geo;

use Backend\Core\Engine\Base\Config as BackendBaseConfig;

/**
 * Class Config
 * @package Backend\Modules\Geo
 */
class Config extends BackendBaseConfig
{

    /**
     * The default action.
     *
     * @var    string
     */
    protected $defaultAction = 'Index';

    /**
     * The disabled actions.
     *
     * @var    array
     */
    protected $disabledActions = array();
}
