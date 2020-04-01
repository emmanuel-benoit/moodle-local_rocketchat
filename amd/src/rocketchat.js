/* eslint-env jquery */
define(['core/ajax', 'local_rocketchat/rocketchat'], function(ajax) {
    var module = {};

    var set_rocketchat_course_sync = function(course_id, pendingsync) {
        ajax.call([
        {
            methodname: 'local_rocketchat_set_rocketchat_course_sync',
            args: {
                courseid: course_id,
                pendingsync: pendingsync
            }
        }
        ]);
    };

    var set_rocketchat_role_sync = function(role_id, requiresync) {
        ajax.call([
        {
            methodname: 'local_rocketchat_set_rocketchat_role_sync',
            args: {
                roleid: role_id,
                requiresync: requiresync
            }
        }
        ]);
    };

    var set_rocketchat_event_based_sync = function(course_id, eventbasedsync) {
        ajax.call([
        {
            methodname: 'local_rocketchat_set_rocketchat_event_based_sync',
            args: {
                courseid: course_id,
                eventbasedsync: eventbasedsync
            }
        }
        ]);
    };

    var manually_trigger_sync = function(courseid) {
        ajax.call([
        {
            methodname: 'local_rocketchat_manually_trigger_sync',
            args: {
                courseid: courseid
            }
        }
        ]);

//        location.reload();
    };

    module.init = function() {
        $('#integrated-roles').on('click', 'input', function() {
            var checkbox = $(this);
            set_rocketchat_role_sync(checkbox.data('roleid'), checkbox.is(":checked"));
        });

        $('#integrated-courses').on('click', 'button', function(e) {
            e.preventDefault();
            $(this).text("Syncing ...");
            $(this).prop("disabled", "disabled");
            var courseid = $(this).data('courseid');
            manually_trigger_sync(courseid, this);
        });

        $('#integrated-courses').on('click', 'input[name="pendingsync"]', function() {
            var checkbox = $(this);
            set_rocketchat_course_sync(checkbox.data('courseid'), checkbox.is(":checked"));
        });

        $('#integrated-courses').on('click', 'input[name="eventbasedsync"]', function() {
            var checkbox = $(this);
            set_rocketchat_event_based_sync(checkbox.data('courseid'), checkbox.is(":checked"));
        });
    };

    window.M.local_rocketchat = module;

    return module;
});