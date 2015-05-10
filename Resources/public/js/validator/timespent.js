/*global define*/
define(['underscore', 'orotranslation/js/translator'
], function (_, __) {
    'use strict';

    var defaultParam = {
        message: 'test message.',
        match: true
    };

    /**
     * @export raorocrmtimelap/js/validator/timespent
     */
    return [
        'RA\\OroCrmTimeLapBundle\\Validator\\Constraints\\TimeSpent',
        function (value, element) {
            var pattern = new RegExp(/^\d+d(\s\d+h(\s\d+m)?)?$|^\d+h(\s\d+m)?$|^\d+m$/),
                param = _.extend({}, defaultParam, param);

            return this.optional(element) || Boolean(param.match) === pattern.test(value);
        },
        function (param, element) {
            var value = this.elementValue(element),
                placeholders = {};
            param = _.extend({}, defaultParam, param);
            placeholders.value = value;
            return __(param.message, placeholders);
        }
    ];
});
