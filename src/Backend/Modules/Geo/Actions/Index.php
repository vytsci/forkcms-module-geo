<?php

namespace Backend\Modules\Geo\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\DataGridArray as BackendDataGridArray;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Geo\Engine\Model as BackendGeoModel;
use Common\Modules\Geo\Engine\Model as CommonGeoModel;
use Common\Modules\Filter\Engine\Helper as CommonFilterHelper;
use Common\Modules\Filter\Engine\Filter;

/**
 * Class Index
 * @package Backend\Modules\Geo\Actions
 */
class Index extends BackendBaseActionIndex
{

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var BackendDataGridArray
     */
    private $dgCountries;

    /**
     *
     */
    public function execute()
    {
        parent::execute();
        $this->loadFilter();
        $this->loadDataGrid();
        $this->parse();
        $this->display();
    }

    /**
     * Loads filter form
     */
    private function loadFilter()
    {
        $this->filter = new Filter();

        $this->filter
            ->addTextCriteria(
                'search',
                array('gcl.name'),
                CommonFilterHelper::OPERATOR_PATTERN
            );
    }

    /**
     * @throws \Exception
     * @throws \SpoonDatagridException
     */
    private function loadDataGrid()
    {
        $countries = BackendGeoModel::getCountriesForDataGrid($this->filter);

        $this->dgCountries = new BackendDataGridArray($countries);

        $this->dgCountries->setSortingColumns($this->dgCountries->getColumns(), 'name');

        $this->dgCountries->setMassActionCheckboxes('check', '[id]');
        $ddmMassAction = new \SpoonFormDropdown(
            'action',
            array(
                'delete' => BL::lbl('Delete'),
                'unpublish' => BL::lbl('Unpublish'),
                'publish' => BL::lbl('Publish'),
            ),
            'delete',
            false,
            'form-control',
            'form-control danger'
        );
        $ddmMassAction->setOptionAttributes(
            'delete',
            array(
                'data-target' => '#confirmDelete',
            )
        );
        $this->dgCountries->setMassAction($ddmMassAction);

        /*if (BackendAuthentication::isAllowedAction('States')) {
            $this->dgCountries->addColumn(
                'details_states',
                null,
                BL::lbl('ManageStates'),
                BackendModel::createURLForAction('States').'&amp;country_id=[id]',
                BL::lbl('ManageStates')
            );
        }*/

        if (BackendAuthentication::isAllowedAction('EditCountry')) {
            $this->dgCountries->addColumn(
                'edit',
                null,
                BL::getLabel('Edit'),
                BackendModel::createURLForAction('EditCountry', null, null, null).'&amp;id=[id]',
                BL::getLabel('Edit')
            );
        }
    }

    /**
     * @throws \SpoonTemplateException
     */
    protected function parse()
    {
        parent::parse();

        $this->filter->parse($this->tpl);

        $this->tpl->assign(
            'dgCountries',
            ($this->dgCountries->getNumResults() != 0) ? $this->dgCountries->getContent() : false
        );
    }
}
