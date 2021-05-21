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

    return function (config) {

        var Conditions = {
            container: $('conditions-container-' + config.row_id),
            template: mageTemplate('#condition-template'),
            count: 0,
            index: 0,
            init: function () {
                config.conditionsData.forEach(function (data) {
                    data.row_id = config.row_id;
                    this.add(data);
                }, this);
                if (config.conditionsData.length == 0) {
                    this.add({
                        row_id: config.row_id
                    });
                }
                this.bindActions();
                return this;
            },
            add: function (data) {
                var element;
                data.condition_id = this.index;
                element = this.template({
                    data: data
                });
                Element.insert(this.container, element);
                this.reloadAttributes();
                this.count++;
                this.index++;
            },
            remove: function (event) {
                if (this.count == 1) {
                    return;
                }
                var element = $(Event.findElement(event, 'tr'));
                if (element) {
                    Element.remove(element);
                    this.count--;
                }
            },
            reloadAttributes: function () {
                this.container.select('.attribute-select').forEach(function (attribute) {
                    if (!attribute.readAttribute('data-loaded')) {
                        attribute.writeAttribute('data-loaded', true);
                        this.reloadAttributeValues(attribute);
                    }
                }, this);
            },
            changeAttribute: function (event) {
                var element = $(Event.findElement(event, 'select'));
                this.reloadAttributeValues(element);
            },
            reloadAttributeValues: function (attribute) {

                var elementName = attribute.readAttribute('data-value');
                var input = jQuery("input[name='" + elementName + "']"),
                    select = jQuery("select[name='" + elementName + "']");

                if (attribute.getValue()) {
                    jQuery.ajax({
                        url: config.url,
                        type: 'post',
                        async: false,
                        dataType: 'json',
                        context: this,
                        data: {
                            'code': attribute.getValue()
                        },
                        success: function (response) {
                            if (response.error) {
                                alert(response.message);
                            } else {
                                if (response.values.length) {
                                    input.attr('disabled', 'disabled').hide();
                                    select.removeAttr('disabled').show().find('option').remove();
                                    response.values.forEach(function (data) {
                                        select.append(jQuery("<option></option>")
                                            .attr("value", data.value)
                                            .text(data.label));
                                    });
                                    if (input.val()) {
                                        select.val(input.val());
                                        input.val('');
                                    }
                                } else {
                                    input.removeAttr('disabled').show();
                                    select.attr('disabled', 'disabled').hide();
                                }
                            }
                        }
                    });
                } else {
                    input.removeAttr('disabled').show();
                    select.attr('disabled', 'disabled').hide();
                }
            },
            bindActions: function () {
                this.container.on('click', '.delete-condition', this.remove.bind(this));
                this.container.on('change', '.attribute-select', this.changeAttribute.bind(this));
            }
        };

        return Conditions.init();
    };
});
