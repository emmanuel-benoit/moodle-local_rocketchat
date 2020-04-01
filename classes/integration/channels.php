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
 * Channels functions for Rocket.Chat API calls.
 *
 * @package     local_rocketchat
 * @copyright   2016 GetSmarter {@link http://www.getsmarter.co.za}
 * @author      2019 Adrian Perez <p.adrian@gmx.ch> {@link https://adrianperez.me}
 * @license     MIT License
 */

namespace local_rocketchat\integration;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot . '/group/externallib.php');

class channels {

    public $errors = array();

    private $client;

    public function __construct($client) {
        $this->client = $client;
    }

    /**
     * @param $rocketchatcourse
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public function create_channels_for_course($rocketchatcourse) {
        global $DB;

        $course = $DB->get_record('course', array("id" => $rocketchatcourse->course));
        $groups = $DB->get_records('groups', array("courseid" => $course->id));

        foreach ($groups as $group) {
            if ($this->_group_requires_rocketchat_channel($group)) {
                $this->_create($this->_get_channel_name($course,$group));
            }
        }
    }

    /**
     * @param $group
     * @return bool
     * @throws \dml_exception
     */
    public function has_channel_for_group($group) {
        global $DB;
        $course = $DB->get_record('course', array("id" => $group->courseid));
        return $this->has_private_group($this->_get_channel_name($course,$group));
    }

    /**
     * @param $course the course the group is a part of
     * @param $group the group to get a channel name from
     * @return string the channel's name
     * @throws \dml_exception
     */
    private function _get_channel_name($course,$group) {
        return preg_replace( '/[^\w\-]/' , '_' ,
                $course->shortname . " - " . $group->name );
    }

    /**
     * @param $name
     * @return bool
     * @throws \dml_exception
     */
    public function has_private_group($name) {
        $api = '/api/v1/groups.info?roomName=' . $name;

        $header = $this->client->authentication_headers();

        $response = \local_rocketchat\utilities::make_request($this->client->url, $api, 'get', null, $header);

        if ($response->success) {
            return $response->group->_id;
        }

        return false;
    }

    /**
     * @param $channel
     * @throws \coding_exception
     * @throws \dml_exception
     */
    private function _create($channel) {
        if (!$this->_channel_exists($channel)) {
            $this->_create_channel($channel);
        }
    }

    /**
     * @param $channelname
     * @return bool
     * @throws \dml_exception
     */
    private function _channel_exists($channelname) {
        foreach ($this->_existing_channels() as $channel) {
            if ($channel->name == $channelname) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     * @throws \dml_exception
     */
    private function _existing_channels() {
        $api = '/api/v1/rooms.get';

        $header = $this->client->authentication_headers();
        array_push($header, 'Content-Type: application/json');

        $response = \local_rocketchat\utilities::make_request($this->client->url, $api, 'get', null, $header);

        return $response->update;
    }

    /**
     * @param $channel
     * @throws \coding_exception
     * @throws \dml_exception
     */
    private function _create_channel($channel) {
        $api = '/api/v1/groups.create';
        $data = array(
            "name" => $channel
        );

        $header = $this->client->authentication_headers();
        array_push($header, 'Content-Type: application/json');

        $response = \local_rocketchat\utilities::make_request($this->client->url, $api, 'post', $data, $header);

        if (!$response->success) {
            $object = new \stdClass();
            $object->code = get_string('channel_creation', 'local_rocketchat');
            $object->error = $response->error;

            array_push($this->errors, $object);
        }
    }

    /**
     * @param $group
     * @return bool
     * @throws \dml_exception
     */
    private function _group_requires_rocketchat_channel($group) {
        $groupregextext = get_config('local_rocketchat', 'groupregex');
        $groupregexs = explode("\r\n", $groupregextext);

        foreach ($groupregexs as $regex) {
            if (preg_match($regex, $group->name)) {
                return true;
            }
        }

        return false;
    }
}
