<?php

namespace Frontend\Modules\Geo;

use Frontend\Core\Engine\Base\Config as FrontendBaseConfig;

/**
 * Class Config
 * @package Frontend\Modules\Geo
 */
class Config extends FrontendBaseConfig
{

    /**
     * The default action
     *
     * @var    string
     */
    protected $defaultAction = null;

    /**
     * The disabled actions
     *
     * @var    array
     */
    protected $disabledActions = array();
}
