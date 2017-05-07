<?php

namespace Backend\Modules\Geo\Ajax;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Core\Engine\Language as BL;
use Common\Modules\Geo\Engine\Helper as CommonGeoHelper;

/**
 * Class GetData
 * @package Backend\Modules\Geo\Ajax
 */
class GetData extends BackendBaseAJAXAction
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
                $data = CommonGeoHelper::getArrayCountries(BL::getWorkingLanguage());
                break;
            case 'states':
                $data = CommonGeoHelper::getArrayStates($id, BL::getWorkingLanguage());
                break;
            case 'cities':
                $data = CommonGeoHelper::getArrayCities($id, BL::getWorkingLanguage());
                break;
        }

        $this->output(self::OK, $data);
    }
}
