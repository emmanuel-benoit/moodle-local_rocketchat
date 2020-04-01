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
 * Strings for component 'local_rocketchat', language 'en', branch 'MOODLE_35_STABLE'
 *
 * @package     local_rocketchat
 * @copyright   2016 GetSmarter {@link http://www.getsmarter.co.za}
 * @author      2019 Adrian Perez <p.adrian@gmx.ch> {@link https://adrianperez.me}
 * @license     MIT License
 */

// General.
$string['pluginname'] = 'Rocket.Chat';
$string['scheduledtaskname'] = 'Sync students to Rocket.Chat';
$string['coursesyncresult'] = 'The courseid is - {$a->courseid}  / The pendingsync is {$a->pendingsync}';
$string['coursetriggeryncresult'] = 'Success: The courseid is - {$a->courseid}';
$string['courseeventbasedsyncresult'] = 'The courseid is - {$a->courseid}  / The eventbasedsync is {$a->eventbasedsync}';
$string['coursesyncparam_courseid'] = 'The course id';
$string['coursesyncparam_roleid'] = 'The role id';
$string['coursesyncparam_pendingsync'] = 'Highlights if a course is pending sync';
$string['coursesyncparam_requiresync'] = 'Highlights if a course is requiring sync';
$string['coursesyncparam_enentbasedsync'] = 'Highlights if a course has event based sync active';
$string['sync_returns'] = 'Whether or not the update was successful or not';
$string['auth_failure'] = 'Rocket.Chat Integration - authentication failure';
$string['connection_failure'] = 'Failed to establish a client connection with the Rocket.Chat server';
$string['user_creation'] = 'Rocket.Chat Integration - user creation';
$string['channel_creation'] = 'Rocket.Chat Integration - channel creation';
$string['subscription_creation'] = 'Rocket.Chat Integration - subscription creation';

// Settings page.
$string['heading_api'] = 'API Connection';
$string['heading_sync'] = 'Sync Configuration';
$string['hostname'] = 'Hostname';
$string['hostname_desc'] = 'Please specify the fully qualified domain name to Rocket.Chat instance.';
$string['port'] = 'Port';
$string['port_desc'] = 'Please specify the port if it\'s not the default (3000).';
$string['protocol'] = 'Protocol';
$string['protocol_desc'] = 'Please specify the HTTP protocol if it\'s not the default (https).';
$string['usetoken'] = 'Token-based authentication';
$string['usetoken_desc'] = 'Authenticate using an user ID / token pair instead of an username and password.';
$string['username'] = 'Username';
$string['username_desc'] = 'Username or user ID for accessing the API. Please create a Rocket.Chat Moodle user for this purpose.';
$string['password'] = 'Password';
$string['password_desc'] = 'Password for the given user, or token if token-based authentication is enabled.';
$string['groupregex'] = 'Group Regex Filters';
$string['groupregex_desc'] = 'Used for matching which groups should be sync to Rocket.Chat:<ul><li>/all/</li><li>/coach group [a-z][0-9]/</li><li>/example project group [0-9][0-9]/</li></ul>';
$string['syncusers'] = 'Synchronize users';
$string['syncusers_desc'] = 'Attempt to create Rocket.Chat users from Moodle. Users need to be synchronized externally unless this is checked.';

// Course integration.
$string['heading_course'] = 'Course Integration';
$string['course_desc'] = 'Manage integration between Moodle and Rocket.Chat. Specify which users and courses require Rocket.Chat integration and manually trigger sync.';
$string['coursetable_column_1'] = 'Course';
$string['coursetable_column_2'] = 'Event Based Sync';
$string['coursetable_column_3'] = 'Pending Sync';
$string['coursetable_column_4'] = 'Last Sync Date';
$string['courseinfo_1'] = 'Courses with event based sync active will be affected by certain events - group_member_added, group_member_removed and user_enrolment_updated. Ensure that you have done an initial sync before turning it on.';
$string['courseinfo_2'] = 'Courses pending sync will be sync\'d to rocketchat on the next cron execution in the background. Pending sync will be removed after syncing.';
$string['courseinfo_3'] = 'Hovering on the three dots will display any errors.';
$string['courseinfo_4'] = 'Manual sync execution will be run immediately';
$string['button_sync'] = 'Manual Sync';

// Role integration.
$string['heading_role'] = 'Role Integration';
$string['role_desc'] = 'Manage integration between Moodle and Rocket.Chat. Specify which roles are included in the Rocket.Chat integration.';
$string['roletable_column_1'] = 'Course';
$string['roletable_column_2'] = 'Requires Sync';
$string['roleinfo_1'] = 'Checked roles will be included in sync. Removing a role will not remove users already uploaded to Rocket.Chat.';
