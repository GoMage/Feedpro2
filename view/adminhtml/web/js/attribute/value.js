/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.3.0
 * @since        Class available since Release 1.0.0
 */

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
