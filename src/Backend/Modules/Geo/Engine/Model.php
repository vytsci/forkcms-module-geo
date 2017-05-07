<?php

namespace Backend\Modules\Geo\Engine;

use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;
use Common\Modules\Filter\Engine\Filter;

/**
 * Class Model
 * @package Backend\Modules\Geo\Engine
 */
class Model
{
    /**
     *
     */
    const QRY_DG_COUNTRIES =
        'SELECT
            gc.id, gc.hidden AS is_hidden, gcl.name, gc.continent_code, gc.iso_alpha_3, gc.iso_numeric
        FROM geo_countries AS gc
        INNER JOIN geo_countries_locale AS gcl ON gcl.id = gc.id AND gcl.language = ?';

    /**
     *
     */
    const QRY_DG_STATES =
        'SELECT
            *
        FROM geo_states AS gs
        INNER JOIN geo_states_locale AS gsl ON gsl.id = gs.id AND gsl.language = ?';

    /**
     *
     */
    const QRY_DG_CITIES =
        'SELECT
            *
        FROM geo_cities AS gct
        INNER JOIN geo_cities_locale AS gctl ON gctl.id = gct.id AND gctl.language = ?';

    /**
     * @param Filter $filter
     * @return array
     * @throws \SpoonDatabaseException
     */
    public static function getCountriesForDataGrid(Filter $filter)
    {
        $result = (array)BackendModel::getContainer()->get('database')->getRecords(
            $filter->getQuery(self::QRY_DG_COUNTRIES),
            array(BL::getWorkingLanguage())
        );

        return $result;
    }

    /**
     * @param Filter $filter
     * @return array
     * @throws \SpoonDatabaseException
     */
    public static function getStatesForDataGrid(Filter $filter)
    {
        $result = (array)BackendModel::getContainer()->get('database')->getRecords(
            $filter->getQuery(self::QRY_DG_STATES),
            array(BL::getWorkingLanguage())
        );

        return $result;
    }

    /**
     * @param Filter $filter
     * @return array
     * @throws \SpoonDatabaseException
     */
    public static function getCitiesForDataGrid(Filter $filter)
    {
        $result = (array)BackendModel::getContainer()->get('database')->getRecords(
            $filter->getQuery(self::QRY_DG_CITIES),
            array(BL::getWorkingLanguage())
        );

        return $result;
    }

    /**
     * @param $ids
     * @throws \SpoonDatabaseException
     */
    public static function deleteCountries($ids)
    {
        BackendModel::getContainer()->get('database')->delete('geo_countries', 'id IN ('.implode(', ', $ids).')');
    }

    /**
     * @param $ids
     * @throws \SpoonDatabaseException
     */
    public static function deleteStates($ids)
    {
        BackendModel::getContainer()->get('database')->delete('geo_states', 'id IN ('.implode(', ', $ids).')');
    }

    /**
     * @param $ids
     * @throws \SpoonDatabaseException
     */
    public static function deleteCities($ids)
    {
        BackendModel::getContainer()->get('database')->delete('geo_cities', 'id IN ('.implode(', ', $ids).')');
    }

    /**
     * @param $ids
     * @throws \SpoonDatabaseException
     */
    public static function unpublishCountries($ids)
    {
        BackendModel::getContainer()->get('database')->update(
            'geo_countries',
            array('hidden' => 1),
            'id IN ('.implode(', ', $ids).')'
        );
    }

    /**
     * @param $ids
     * @throws \SpoonDatabaseException
     */
    public static function publishCountries($ids)
    {
        BackendModel::getContainer()->get('database')->update(
            'geo_countries',
            array('hidden' => 0),
            'id IN ('.implode(', ', $ids).')'
        );
    }
}
