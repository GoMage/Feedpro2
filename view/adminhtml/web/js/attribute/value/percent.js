/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.2.0
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
        var Percent = {
            template: mageTemplate('#percent-value-template'),
            init: function () {
                var element, data;
                data = {
                    row_id: row_id,
                    percent: (typeof value.percent == 'undefined') ? '' : value.percent,
                    code: (typeof value.code == 'undefined') ? '' : value.code
                };
                element = this.template({
                    data: data
                });
                Element.insert(container, element);
            }
        };
        Percent.init();
    };
});
