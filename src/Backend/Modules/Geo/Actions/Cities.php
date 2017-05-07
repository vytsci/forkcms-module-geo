<?php

namespace Backend\Modules\Geo\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\DataGridArray as BackendDataGridArray;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Geo\Engine\Model as BackendGeoModel;
use Common\Modules\Filter\Engine\Helper as CommonFilterHelper;
use Common\Modules\Filter\Engine\Criteria as CommonFilterCriteria;
use Common\Modules\Filter\Engine\Filter;

use Common\Modules\Geo\Entity\State;

/**
 * Class Cities
 * @package Backend\Modules\Geo\Actions
 */
class Cities extends BackendBaseActionIndex
{

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var BackendDataGridArray
     */
    private $dgCities;

    /**
     * @var State
     */
    private $state;

    /**
     *
     */
    public function execute()
    {
        parent::execute();

        $this->state = new State(array($this->getParameter('state_id')), array(BL::getWorkingLanguage()));

        if (!$this->state->isLoaded()) {
            $this->redirect(BackendModel::createURLForAction('Index').'&error=non-existing');
        }

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
                array('gctl.name'),
                CommonFilterHelper::OPERATOR_PATTERN
            )
            ->addCriteria(
                new CommonFilterCriteria(
                    'state',
                    array('gct.state_id'),
                    CommonFilterHelper::OPERATOR_EQUAL,
                    null,
                    $this->state->getId()
                )
            );
    }

    /**
     * @throws \Exception
     * @throws \SpoonDatagridException
     */
    private function loadDataGrid()
    {
        $cities = BackendGeoModel::getCitiesForDataGrid($this->filter);

        $this->dgCities = new BackendDataGridArray($cities);

        $this->dgCities->setColumnsHidden(array('id', 'state_id'));

        $this->dgCities->setMassActionCheckboxes('check', '[id]');
        $ddmMassAction = new \SpoonFormDropdown(
            'action',
            array('delete' => BL::lbl('Delete')),
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
        $this->dgCities->setMassAction($ddmMassAction);

        if (BackendAuthentication::isAllowedAction('Edit')) {
            $this->dgCities->addColumn(
                'edit',
                null,
                BL::getLabel('Edit'),
                BackendModel::createURLForAction('EditCity', null, null, null)
                .'&amp;id=[id]',
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

        $this->tpl->assign('state', $this->state->toArray());

        $this->tpl->assign(
            'dgCities',
            ($this->dgCities->getNumResults() != 0) ? $this->dgCities->getContent() : false
        );
    }
}
