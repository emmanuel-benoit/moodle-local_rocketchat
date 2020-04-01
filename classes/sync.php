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
 * Sync functions for Rocket.Chat integration.
 *
 * @package     local_rocketchat
 * @copyright   2016 GetSmarter {@link http://www.getsmarter.co.za}
 * @author      2019 Adrian Perez <p.adrian@gmx.ch> {@link https://adrianperez.me}
 * @license     MIT License
 */

namespace local_rocketchat;

defined('MOODLE_INTERNAL') || die;

class sync {

    private $client;
    private $errors = array();

    /**
     * sync constructor.
     *
     * @throws \dml_exception
     */
    public function __construct() {
        $this->client = new client();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \moodle_exception
     */
    public function sync_pending_courses() {
        global $DB;

        $rocketchatcourses = $DB->get_records('local_rocketchat_courses', array("pendingsync" => true), '', '*');

        foreach ($rocketchatcourses as $rocketchatcourse) {
            $this->sync_pending_course($rocketchatcourse->course);
        }
    }

    /**
     * @param $courseid
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \moodle_exception
     */
    public function sync_pending_course($courseid) {
        global $DB;

        $rocketchatcourse = $DB->get_record('local_rocketchat_courses', array("course" => $courseid));

        if (!$rocketchatcourse) {
            $rocketchatcourseid = $this->create_rocketchat_course($courseid);
            $rocketchatcourse = $DB->get_record('local_rocketchat_courses', array("id" => $rocketchatcourseid));
        }

        $this->_run_sync($rocketchatcourse);
        $this->_record_result($rocketchatcourse);
    }

    /**
     * @param $courseid
     * @return bool|int
     * @throws \dml_exception
     */
    private function create_rocketchat_course($courseid) {
        global $DB;

        $rocketchatcourse = new \stdClass();
        $rocketchatcourse->course = $courseid;
        $rocketchatcourse->pendingsync = true;
        $rocketchatcourseid = $DB->insert_record('local_rocketchat_courses', $rocketchatcourse);

        return $rocketchatcourseid;
    }

    /**
     * @param $rocketchatcourse
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \moodle_exception
     */
    private function _run_sync($rocketchatcourse) {
        global $DB;

        $course = $DB->get_record('course', array("id" => $rocketchatcourse->course));

        if ($this->client->authenticated) {
            $channelapi = new integration\channels($this->client);
            $channelapi->create_channels_for_course($rocketchatcourse);
            $this->errors = array_merge($this->errors, $channelapi->errors);

            if (get_config('local_rocketchat', 'syncusers')) {
                $userapi = new integration\users($this->client);
                $userapi->create_users_for_course($rocketchatcourse);
                $this->errors = array_merge($this->errors, $userapi->errors);
            }

            $subscriptionapi = new integration\subscriptions($this->client);
            $subscriptionapi->add_subscriptions_for_course($course);
            $this->errors = array_merge($this->errors, $subscriptionapi->errors);
        } else {
            $object = array();
            $object->code = get_string('auth_failure', 'local_rocketchat');
            $object->error = get_string('connection_failure', 'local_rocketchat');
            array_push($this->errors, $object);
        }
    }

    /**
     * @param $rocketchatcourse
     * @throws \dml_exception
     */
    private function _record_result($rocketchatcourse) {
        if (count($this->errors) == 0) {
            $this->_pass_sync($rocketchatcourse);
        } else {
            $this->_fail_sync($rocketchatcourse);
        }

        $this->_reset_errors();
    }

    /**
     * @param $rocketchatcourse
     * @throws \dml_exception
     */
    private function _pass_sync($rocketchatcourse) {
        global $DB;

        $rocketchatcourse->pendingsync = 0;
        $rocketchatcourse->lastsync = time();
        $rocketchatcourse->error = null;

        $DB->update_record('local_rocketchat_courses', $rocketchatcourse);
    }

    /**
     * @param $rocketchatcourse
     * @throws \dml_exception
     */
    private function _fail_sync($rocketchatcourse) {
        global $DB;

        $errorstring = "";
        foreach ($this->errors as $error) {
            $errorstring = $errorstring . "[" . $error->code  . "] " . $error->error . "\r\n";
        }

        $rocketchatcourse->pendingsync = 0;
        $rocketchatcourse->lastsync = time();
        $rocketchatcourse->error = $errorstring;

        $DB->update_record('local_rocketchat_courses', $rocketchatcourse);
    }

    private function _reset_errors() {
        $this->errors = array();
    }
}
