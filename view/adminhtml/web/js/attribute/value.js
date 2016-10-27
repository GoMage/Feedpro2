/* eslint-disable no-undef */
// jscs:disable jsDoc

define([
    'GoMage_Feed/js/attribute/value/static',
    'GoMage_Feed/js/attribute/value/percent',
    'GoMage_Feed/js/attribute/value/configurable',
    'GoMage_Feed/js/attribute/value/attribute'
], function (Static, Percent, Configurable, Attribute) {
    'use strict';

    return function (config) {
        var Value = {
            container: $('values-container-' + config.row_id),
            init: function () {
                this.container.innerHTML = '';
                switch (parseInt(config.type)) {
                    case 5:
                        var value = (typeof config.value == 'undefined') ? [] : config.value;
                        Attribute(this.container, config.row_id, value);
                        break;
                    case 6:
                        var value = (typeof config.value == 'undefined') ? {} : config.value;
                        Percent(this.container, config.row_id, value);
                        break;
                    case 7:
                        var value = (typeof config.value == 'undefined') ? {} : config.value;
                        Configurable(this.container, config.row_id, value);
                        break;
                    case 2:
                    default:
                        var value = (typeof config.value == 'undefined') ? '' : config.value;
                        Static(this.container, config.row_id, value);
                        break;
                }
            }
        };
        Value.init();
    };
});
