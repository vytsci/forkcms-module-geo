<?php

namespace Common\Modules\Geo\Engine;

use Common\Core\Model as CommonModel;
use Common\Modules\Geo\Entity\Country;
use Common\Modules\Geo\Entity\CountryLocale;
use Common\Modules\Geo\Entity\State;
use Common\Modules\Geo\Entity\StateLocale;
use Common\Modules\Geo\Entity\City;
use Common\Modules\Geo\Entity\CityLocale;

/**
 * Class Model
 * @package Common\Modules\Geo\Engine
 */
class Model
{
    const TBL_COUNTRIES = 'geo_countries';

    const TBL_COUNTRIES_LOCALE = 'geo_countries_locale';

    const TBL_STATES = 'geo_states';

    const TBL_STATES_LOCALE = 'geo_states_locale';

    const TBL_CITIES = 'geo_cities';

    const TBL_CITIES_LOCALE = 'geo_cities_locale';

    const QRY_ENTITY_COUNTRY = 'SELECT gc.* FROM geo_countries AS gc WHERE gc.id = ?';

    const QRY_ENTITY_COUNTRY_LOCALE =
        'SELECT gcl.* FROM geo_countries_locale AS gcl WHERE gcl.id = ? AND gcl.language = ?';

    const QRY_ENTITY_STATE = 'SELECT gs.* FROM geo_states AS gs WHERE gs.id = ?';

    const QRY_ENTITY_STATE_LOCALE =
        'SELECT gsl.* FROM geo_states_locale AS gsl WHERE gsl.id = ? AND gsl.language = ?';

    const QRY_ENTITY_CITY = 'SELECT gct.* FROM geo_cities AS gct WHERE gct.id = ?';

    const QRY_ENTITY_CITY_LOCALE =
        'SELECT gctl.* FROM geo_cities_locale AS gctl WHERE gctl.id = ? AND gctl.language = ?';

    const QRY_COUNTRIES =
        'SELECT gc.*, gcl.* FROM geo_countries AS gc
        LEFT JOIN geo_countries_locale AS gcl ON gcl.id = gc.id AND gcl.language = ?
        WHERE gc.hidden = 0
        ORDER BY gcl.name ASC';

    const QRY_STATES =
        'SELECT gs.*, gsl.* FROM geo_states AS gs
        LEFT JOIN geo_states_locale AS gsl ON gsl.id = gs.id AND gsl.language = ?
        WHERE gs.country_id = ?
        ORDER BY gsl.name ASC';

    const QRY_CITIES =
        'SELECT gct.*, gctl.* FROM geo_cities AS gct
        LEFT JOIN geo_cities_locale AS gctl ON gctl.id = gct.id AND gctl.language = ?
        WHERE gct.state_id = ?
        ORDER BY gctl.name ASC';

    private static $countries = array();

    private static $states = array();

    private static $cities = array();

    public static function getCountry($id, $language)
    {
        self::parseCountries($language);

        return isset(self::$countries[$id]) ? self::$countries[$id] : null;
    }

    public static function getCountries($language)
    {
        self::parseCountries($language);

        return self::$countries;
    }

    public static function getStates($id, $language)
    {
        self::parseStates($id, $language);

        return isset(self::$states[$id]) ? self::$states[$id] : null;
    }

    public static function getCities($id, $language)
    {
        self::parseCities($id, $language);

        return isset(self::$cities[$id]) ? self::$cities[$id] : null;
    }

    private static function parseCountries($language)
    {
        if (empty(self::$countries)) {
            $db = CommonModel::getContainer()->get('database');

            $count = (int)$db->getVar('SELECT COUNT(*) FROM '.self::TBL_COUNTRIES);
            if ($count == 0) {
                self::collectCountries($language);
            }

            $records = (array)$db->getRecords(self::QRY_COUNTRIES, array($language), 'id');

            foreach ($records as $id => $record) {
                $country = new Country();
                $country->assemble($record);

                $countryLocale = new CountryLocale();
                $countryLocale->assemble($record);

                $country->setLocale($countryLocale, $language);

                self::$countries[$id] = $country;
            }
        }

        return self::$countries;
    }

    private static function parseStates($countryId, $language)
    {
        if (!isset(self::$states[$countryId])) {
            $db = CommonModel::getContainer()->get('database');

            $records = (array)$db->getRecords(self::QRY_STATES, array($language, $countryId), 'id');

            if (empty($records)) {
                self::collectChildren($countryId, 'states', 'country_id', $language);

                $records = (array)$db->getRecords(self::QRY_STATES, array($language, $countryId), 'id');
            }

            foreach ($records as $id => $record) {
                $state = new State();
                $state->assemble($record);

                $stateLocale = new StateLocale();
                $stateLocale->assemble($record);

                $state->setLocale($stateLocale, $language);

                self::$states[$countryId][$id] = $state;

                if (isset(self::$countries[$countryId])) {
                    self::$countries[$countryId]->addState(self::$states[$countryId][$id]);
                }
            }
        }

        return self::$states[$countryId];
    }

    private static function parseCities($stateId, $language)
    {
        if (!isset(self::$cities[$stateId])) {
            $db = CommonModel::getContainer()->get('database');

            $records = (array)$db->getRecords(self::QRY_CITIES, array($language, $stateId), 'id');

            if (empty($records)) {
                self::collectChildren($stateId, 'cities', 'state_id', $language);

                $records = (array)$db->getRecords(self::QRY_CITIES, array($language, $stateId), 'id');
            }

            foreach ($records as $id => $record) {
                $city = new City();
                $city->assemble($record);

                $cityLocale = new CityLocale();
                $cityLocale->assemble($record);

                $city->setLocale($cityLocale, $language);

                self::$cities[$stateId][$id] = $city;

                if (isset(self::$states[$stateId])) {
                    self::$states[$stateId]->addCity(self::$cities[$stateId][$id]);
                }
            }
        }

        return self::$cities[$stateId];
    }


    private static function collectCountries($language)
    {
        $geonames = new \Geonames\Geonames(
            CommonModel::getContainer()->get('fork.settings')->get('Geo', 'username')
        );
        $geonames->url = 'http://api.geonames.org';
        $data = $geonames->countryInfo(array('lang' => strtolower($language)));

        if (!empty($data) && is_array($data)) {
            $queryCountries =
                'REPLACE INTO geo_countries (
                    `id`,
                    `continent_code`,
                    `iso_numeric`,
                    `iso_alpha_3`,
                    `postal_code_format`,
                    `currency_code`
                ) VALUES ';
            $queryCountriesLocale = 'INSERT IGNORE INTO geo_countries_locale (`id`, `language`, `name`) VALUES ';

            $valuesCountries = array();
            $valuesCountriesLocale = array();
            foreach ($data as $value) {
                if ($value instanceof \stdClass) {
                    $valuesCountries[$value->geonameId] =
                        '('
                        .'\''.$value->geonameId.'\', '
                        .'\''.$value->continent.'\', '
                        .'\''.$value->isoNumeric.'\', '
                        .'\''.$value->isoAlpha3.'\', '
                        .(isset($value->postalCodeFormat) ? '\''.$value->postalCodeFormat.'\'' : 'NULL').', '
                        .'\''.$value->currencyCode.'\''
                        .')';

                    $keyCountriesLocale = $value->geonameId.'_'.$language;
                    $valuesCountriesLocale[$keyCountriesLocale] =
                        '('
                        .'\''.$value->geonameId.'\', '
                        .'\''.$language.'\', '
                        .'\''.htmlspecialchars($value->countryName, ENT_QUOTES).'\''
                        .')';
                }
            }

            $queryCountries .= implode(', ', $valuesCountries).';';
            $queryCountriesLocale .= implode(', ', $valuesCountriesLocale).';';

            $query = $queryCountries."\r\n".$queryCountriesLocale;

            CommonModel::getContainer()->get('database')->execute($query);
        }
    }

    private static function collectChildren($id, $section, $parentKey, $language)
    {
        $geonames = new \Geonames\Geonames(
            CommonModel::getContainer()->get('fork.settings')->get('Geo', 'username')
        );
        $geonames->url = 'http://api.geonames.org';

        $parameters = array(
            'lang' => strtolower($language),
            'geonameId' => $id,
        );

        $data = null;
        try {
            $data = $geonames->children($parameters);
        } catch (\Exception $e) {

        }

        $query = null;
        if (isset($data) && is_array($data)) {
            $queryChildren =
                'REPLACE INTO geo_'.$section.' (
                    `id`,
                    `'.$parentKey.'`,
                    `lat`,
                    `lng`,
                    `fcode`
                ) VALUES ';
            $queryChildrenLocale = 'INSERT IGNORE INTO geo_'.$section.'_locale (`id`, `language`, `name`) VALUES ';

            $valuesChildren = array();
            $valuesChildrenLocale = array();
            foreach ($data as $value) {
                if ($value instanceof \stdClass) {
                    $valuesChildren[$value->geonameId] =
                        '('
                        .'\''.$value->geonameId.'\', '
                        .'\''.$id.'\''.', '
                        .'\''.$value->lat.'\', '
                        .'\''.$value->lng.'\', '
                        .'\''.$value->fcode.'\''
                        .')';

                    $keyChildrenLocale = $value->geonameId.'_'.$language;
                    $valuesChildrenLocale[$keyChildrenLocale] =
                        '('
                        .'\''.$value->geonameId.'\', '
                        .'\''.$language.'\', '
                        .'\''.htmlspecialchars($value->name, ENT_QUOTES).'\''
                        .')';
                }
            }

            $queryChildren .= implode(', ', $valuesChildren).';';
            $queryChildrenLocale .= implode(', ', $valuesChildrenLocale).';';

            $query = $queryChildren."\r\n".$queryChildrenLocale;

            CommonModel::getContainer()->get('database')->execute($query);
        }

        return $query;
    }
}
