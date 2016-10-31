/* eslint-disable no-undef */
// jscs:disable jsDoc

define([
    'jquery',
    'GoMage_Feed/js/attribute/value/static',
    'GoMage_Feed/js/attribute/value/percent',
    'GoMage_Feed/js/attribute/value/configurable',
    'GoMage_Feed/js/attribute/value/attribute'
], function (jQuery, Static, Percent, Configurable, Attribute) {
    'use strict';

    return function (config) {
        var Value = {
            container: $('values-container-' + config.row_id),
            object: null,
            init: function () {
                this.container.innerHTML = '';
                var addButton = jQuery('button.add-value[data-row-id="' + config.row_id + '"]');
                switch (parseInt(config.type)) {
                    case 5:
                        var value = (typeof config.value == 'undefined') ? [] : config.value;
                        this.object = Attribute(this.container, config.row_id, value);
                        addButton.show();
                        break;
                    case 6:
                        var value = (typeof config.value == 'undefined') ? {} : config.value;
                        this.object = Percent(this.container, config.row_id, value);
                        addButton.hide();
                        break;
                    case 7:
                        var value = (typeof config.value == 'undefined') ? {} : config.value;
                        this.object = Configurable(this.container, config.row_id, value);
                        addButton.hide();
                        break;
                    case 2:
                    default:
                        var value = (typeof config.value == 'undefined') ? '' : config.value;
                        this.object = Static(this.container, config.row_id, value);
                        addButton.hide();
                        break;
                }

                return this;
            }
        };
        return Value.init();
    };
});
