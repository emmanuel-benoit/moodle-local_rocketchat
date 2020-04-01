<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Rocket.Chat external functions and service definitions.
 *
 * The functions and services defined on this file are
 * processed and registered into the Moodle DB after any
 * install or upgrade operation. All plugins support this.
 *
 * For more information, take a look to the documentation available:
 *     - Webservices API: {@link http://docs.moodle.org/dev/Web_services_API}
 *     - External API: {@link http://docs.moodle.org/dev/External_functions_API}
 *     - Upgrade API: {@link http://docs.moodle.org/dev/Upgrade_API}
 *
 * @package     local_rocketchat
 * @copyright   2016 GetSmarter {@link http://www.getsmarter.co.za}
 * @author      2019 Adrian Perez <p.adrian@gmx.ch> {@link https://adrianperez.me}
 * @license     MIT License
 */

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_rocketchat_set_rocketchat_event_based_sync' => array(
        'classname'   => 'local_rocketchat_external',
        'methodname'  => 'set_rocketchat_event_based_sync',
        'classpath'   => 'local/rocketchat/externallib.php',
        'description' => 'Sets whether or not a course should integrate based on events with rocket chat.',
        'type'        => 'update'
    ),
    'local_rocketchat_set_rocketchat_course_sync' => array(
        'classname'   => 'local_rocketchat_external',
        'methodname'  => 'set_rocketchat_course_sync',
        'classpath'   => 'local/rocketchat/externallib.php',
        'description' => 'Sets whether or not a course should integrate with rocket chat.',
        'type'        => 'update'
    ),
    'local_rocketchat_set_rocketchat_role_sync' => array(
        'classname'   => 'local_rocketchat_external',
        'methodname'  => 'set_rocketchat_role_sync',
        'classpath'   => 'local/rocketchat/externallib.php',
        'description' => 'Sets whether or not a role should integrate with rocket chat.',
        'type'        => 'update'
    ),
    'local_rocketchat_manually_trigger_sync' => array(
        'classname'   => 'local_rocketchat_external',
        'methodname'  => 'manually_trigger_sync',
        'classpath'   => 'local/rocketchat/externallib.php',
        'description' => 'Syncs course with rocket chat.',
        'type'        => 'update'
    )
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
    'Rocket Chat Web Services' => array(
        'functions' => array (
            'local_rocketchat_set_rocketchat_course_sync',
            'local_rocketchat_set_rocketchat_role_sync',
            'local_rocketchat_manually_trigger_sync',
            'local_rocketchat_set_rocketchat_event_based_sync'),
        'restrictedusers'   => 0,
        'enabled'           => 1
        )
    );
