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

use Common\Modules\Geo\Entity\Country;

/**
 * Class States
 * @package Backend\Modules\Geo\Actions
 */
class States extends BackendBaseActionIndex
{

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var BackendDataGridArray
     */
    private $dgStates;

    /**
     * @var Country
     */
    private $country;

    /**
     *
     */
    public function execute()
    {
        parent::execute();

        $this->country = new Country(array($this->getParameter('country_id')), array(BL::getWorkingLanguage()));

        if (!$this->country->isLoaded()) {
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
                array('gsl.name'),
                CommonFilterHelper::OPERATOR_PATTERN
            )
            ->addCriteria(
                new CommonFilterCriteria(
                    'country',
                    array('gs.country_id'),
                    CommonFilterHelper::OPERATOR_EQUAL,
                    null,
                    $this->country->getId()
                )
            );
    }

    /**
     * @throws \Exception
     * @throws \SpoonDatagridException
     */
    private function loadDataGrid()
    {
        $states = BackendGeoModel::getStatesForDataGrid($this->filter);

        $this->dgStates = new BackendDataGridArray($states);

        $this->dgStates->setColumnsHidden(array('id', 'country_id'));

        $this->dgStates->setMassActionCheckboxes('check', '[id]');
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
        $this->dgStates->setMassAction($ddmMassAction);

        if (BackendAuthentication::isAllowedAction('Cities')) {
            $this->dgStates->addColumn(
                'details_cities',
                null,
                BL::lbl('ManageCities'),
                BackendModel::createURLForAction('Cities').'&amp;state_id=[id]',
                BL::lbl('ManageCities')
            );
        }

        /*if (BackendAuthentication::isAllowedAction('AddCity')) {
            $this->dgStates->addColumn(
                'add',
                null,
                BL::getLabel('AddCity'),
                BackendModel::createURLForAction('AddCity', null, null, null)
                .'&amp;state_id=[id]',
                BL::getLabel('AddCity')
            );
        }*/

        if (BackendAuthentication::isAllowedAction('Edit')) {
            $this->dgStates->addColumn(
                'edit',
                null,
                BL::getLabel('Edit'),
                BackendModel::createURLForAction('EditState', null, null, null)
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

        $this->tpl->assign('country', $this->country->toArray());

        $this->tpl->assign(
            'dgStates',
            ($this->dgStates->getNumResults() != 0) ? $this->dgStates->getContent() : false
        );
    }
}
