<?php

namespace Frontend\Modules\Geo\Ajax;

use Frontend\Core\Engine\Base\AjaxAction as FrontendBaseAJAXAction;
use Common\Modules\Geo\Engine\Helper as CommonGeoHelper;

/**
 * Class GetData
 * @package Frontend\Modules\Geo\Ajax
 */
class GetData extends FrontendBaseAJAXAction
{

    /**
     *
     */
    public function execute()
    {
        parent::execute();

        $section = \SpoonFilter::getPostValue('section', array('countries', 'states', 'cities'), null);
        $id = \SpoonFilter::getPostValue('id', null, null);

        if ($section === null) {
            $this->output(self::BAD_REQUEST, null, 'section-parameter is missing.');
            return;
        }

        if (in_array($section, array('states', 'cities')) && empty($id)) {
            $this->output(self::BAD_REQUEST, null, 'id-parameter is missing.');
            return;
        }

        $data = array();
        switch ($section) {
            case 'countries':
                $data = CommonGeoHelper::getArrayCountries(FRONTEND_LANGUAGE);
                break;
            case 'states':
                $data = CommonGeoHelper::getArrayStates($id, FRONTEND_LANGUAGE);
                break;
            case 'cities':
                $data = CommonGeoHelper::getArrayCities($id, FRONTEND_LANGUAGE);
                break;
        }

        $this->output(self::OK, $data);
    }
}
