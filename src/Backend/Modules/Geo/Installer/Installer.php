<?php

namespace Backend\Modules\Geo\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * Class Installer
 * @package Backend\Modules\Geo\Installer
 */
class Installer extends ModuleInstaller
{

    /**
     *
     */
    public function install()
    {
        $this->importSQL(dirname(__FILE__).'/Data/install.sql');

        $this->addModule('Geo');

        $this->importLocale(dirname(__FILE__).'/Data/locale.xml');

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationMembersId = $this->setNavigation($navigationModulesId, 'Geo');
        $this->setNavigation(
            $navigationMembersId,
            'Countries',
            'geo/index',
            array(
                'geo/add_country',
                'geo/edit_country',
                'geo/states',
                'geo/add_state',
                'geo/edit_state',
                'geo/cities',
                'geo/add_city',
                'geo/edit_city',
            )
        );

        $navigationSettingsId = $this->setNavigation(null, 'Settings');
        $navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
        $this->setNavigation($navigationModulesId, 'Geo', 'geo/settings');
    }
}
