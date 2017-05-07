<?php

namespace Backend\Modules\Geo\Actions;

use Backend\Core\Engine\Base\Action as BackendBaseAction;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Geo\Engine\Model as BackendGeoModel;

/**
 * Class MassActionStates
 * @package Backend\Modules\Geo\Actions
 */
class MassActionCities extends BackendBaseAction
{

    /**
     * @throws \Common\Exception\RedirectException
     * @throws \Exception
     */
    public function execute()
    {
        parent::execute();

        $action = \SpoonFilter::getGetValue('action', array('delete'), 'delete');

        if (!isset($_GET['id'])) {
            $this->redirect(
                BackendModel::createURLForAction('Index').'&error=no-selection'
            );
        } else {
            $ids = (array)$_GET['id'];

            if ($action == 'delete') {
                BackendGeoModel::deleteCities($ids);
            }
        }

        $this->redirect(BackendModel::createURLForAction('Children').'&report=deleted');
    }
}
