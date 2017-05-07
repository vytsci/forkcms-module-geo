<?php

namespace Common\Modules\Geo\Engine;

/**
 * Class Helper
 * @package Common\Modules\Geo\Engine
 */
class Helper
{
    /**
     * @param $language
     * @return array
     */
    public static function getArrayCountries($language)
    {
        $result = array();

        $countries = Model::getCountries($language);

        foreach ($countries as $country) {
            $result[] = $country->toArray();
        }

        return $result;
    }

    /**
     * @param $countryId
     * @param $language
     * @return array
     */
    public static function getArrayStates($countryId, $language)
    {
        $result = array();

        $states = Model::getStates($countryId, $language);

        foreach ($states as $state) {
            $result[] = $state->toArray();
        }

        return $result;
    }

    /**
     * @param $stateId
     * @param $language
     * @return array
     */
    public static function getArrayCities($stateId, $language)
    {
        $result = array();

        $cities = Model::getCities($stateId, $language);

        foreach ($cities as $city) {
            $result[] = $city->toArray();
        }

        return $result;
    }

    /**
     * @param $language
     * @return array
     */
    public static function getCountriesForDropdown($language)
    {
        $result = array();

        $countries = Model::getCountries($language);

        foreach ($countries as $country) {
            $result[$country->getId()] = $country->getLocale($language)->getName();
        }

        return $result;
    }

    /**
     * @param $countryId
     * @param $language
     * @return array
     */
    public static function getStatesForDropdown($countryId, $language)
    {
        $result = array();

        $states = Model::getStates($countryId, $language);

        foreach ($states as $state) {
            $result[$state->getId()] = $state->getLocale($language)->getName();
        }

        return $result;
    }

    /**
     * @param $stateId
     * @param $language
     * @return array
     */
    public static function getCitiesForDropdown($stateId, $language)
    {
        $result = array();

        $cities = Model::getCities($stateId, $language);

        foreach ($cities as $city) {
            $result[$city->getId()] = $city->getLocale($language)->getName();
        }

        return $result;
    }

    /**
     * @param \SpoonTemplate $tpl
     */
    public static function mapTemplateModifiers(\SpoonTemplate $tpl)
    {

    }
}
