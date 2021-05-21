/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.1
 * @since        Class available since Release 1.0.0
 */

define([
    'jquery',
    'mage/template',
    'jquery/ui',
    'prototype'
], function (jQuery, mageTemplate) {
    'use strict';

    return function (container, row_id, value) {
        var Static = {
            template: mageTemplate('#static-value-template'),
            init: function () {
                var element, data;
                data = {
                    row_id: row_id,
                    value: value
                };
                element = this.template({
                    data: data
                });
                Element.insert(container, element);
            }
        };
        Static.init();
    };
});
