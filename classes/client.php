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
 * Client functions for Rocket.Chat authentication.
 *
 * @package     local_rocketchat
 * @copyright   2016 GetSmarter {@link http://www.getsmarter.co.za}
 * @author      2019 Adrian Perez <p.adrian@gmx.ch> {@link https://adrianperez.me}
 * @license     MIT License
 */

namespace local_rocketchat;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/filelib.php');

class client {

    public $authenticated = false;
    public $host;
    public $port;
    public $httpOnly;
    public $url;

    private $useToken;
    private $authToken;
    private $userId;
    private $username;
    private $password;
    private $api;

    /**
     * Client constructor to get settings for API calls.
     *
     * @throws \dml_exception
     */
    public function __construct() {
        $this->host = get_config('local_rocketchat', 'host');
        $this->port = get_config('local_rocketchat', 'port');
        $this->httpOnly = get_config('local_rocketchat', 'protocol');
        $this->useToken = get_config('local_rocketchat', 'usetoken');
        $this->username = get_config('local_rocketchat', 'username');
        $this->password = get_config('local_rocketchat', 'password');

        $this->url = 'http' . ( $this->httpOnly ? '' : 's' ) . '://'
            . $this->host . ':' . $this->port;
        $this->api = '';

        if ($this->useToken) {
            $this->userId = $this->username;
            $this->authToken = $this->password;
            $this->authenticated = true;
        } else {
            $this->authenticate();
        }
    }

    public function authentication_headers() {
        return array(
            "X-Auth-Token: " . $this->authToken,
            "X-User-Id: " . $this->userId);
    }

    /**
     * @throws \dml_exception
     */
    private function authenticate() {
        $response = $this->request_login_credentials();

        if ($response && $response->status == 'success') {
            $this->store_credentials($response->data);
            $this->authenticated = true;
        }
    }

    /**
     * @return bool|mixed
     * @throws \dml_exception
     */
    private function request_login_credentials() {
        $api = '/api/v1/login';
        $data = array(
            "user" => $this->username,
            "password" => $this->password
        );

        $header = array('Content-Type: application/json');

        return utilities::make_request($this->url, $api, 'post', $data, $header);
    }

    private function store_credentials($data) {
        if (isset($data->authToken) && isset($data->userId)) {
            $this->authToken = $data->authToken;
            $this->userId = $data->userId;
        }
    }
}
