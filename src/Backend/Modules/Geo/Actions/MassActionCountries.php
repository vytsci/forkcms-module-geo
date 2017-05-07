<?php

namespace Backend\Modules\Geo\Actions;

use Backend\Core\Engine\Base\Action as BackendBaseAction;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Geo\Engine\Model as BackendGeoModel;

/**
 * Class MassActionCountries
 * @package Backend\Modules\Geo\Actions
 */
class MassActionCountries extends BackendBaseAction
{

    /**
     * @throws \Common\Exception\RedirectException
     * @throws \Exception
     */
    public function execute()
    {
        parent::execute();

        $action = \SpoonFilter::getGetValue('action', array('delete', 'unpublish', 'publish'), 'delete');

        $report = null;
        if (!isset($_GET['id'])) {
            $this->redirect(
                BackendModel::createURLForAction('Index').'&error=no-selection'
            );
        } else {
            $ids = (array)$_GET['id'];

            switch ($action) {
                case 'delete':
                    BackendGeoModel::deleteCountries($ids);
                    $report = 'deleted';
                    break;
                case 'unpublish':
                    BackendGeoModel::unpublishCountries($ids);
                    $report = 'unpublished';
                    break;
                case 'publish':
                    BackendGeoModel::publishCountries($ids);
                    $report = 'published';
                    break;
            }

            if ($action == 'delete') {

            }
        }

        $this->redirect(BackendModel::createURLForAction('Index').'&report='.$report);
    }
}
