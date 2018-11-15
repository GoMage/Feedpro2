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
    'mage/translate',
    'jquery/ui',
    'prototype'
], function (jQuery, mageTemplate, translate) {
    'use strict';

    return function (config) {
        var Rows = {
            container: $('rows-container'),
            template: mageTemplate('#row-template'),
            titleTemplate: mageTemplate('#title-template'),
            index: 0,
            init: function () {
                config.rowsData.forEach(function (data) {
                    this.add(data);
                }, this);
                if (config.rowsData.length == 0) {
                    this.add({});
                }
                this.bindActions();
            },
            add: function (data) {
                var element;
                data.row_id = this.index;
                data.output = (typeof data.output == 'undefined') ? [] : data.output;
                element = this.template({
                    data: data
                });
                Element.insert(this.container, element);
                this.setValues();
                this.setTitle(data.row_id);
                this.index++;
            },
            remove: function (event) {
                var element = $(Event.findElement(event, 'div.fm-block'));
                if (element) {
                    Element.remove(element);
                }
            },
            toggleEdit: function (event) {
                var element = $(Event.findElement(event, 'div.fm-block'));
                if (element) {
                    var row_id = element.readAttribute('data-row-id');
                    this.setTitle(row_id);
                    //this.validateElement(element);
                    element.toggleClassName('__opened');
                    var self = row_id;
                }
                this.container.childElements().forEach(function (data) {
                    if (self !== data.readAttribute('data-row-id')) {
                        if (data.classList.contains('__opened')) {
                            this.validateElement(data);
                            data.classList.remove('__opened');
                        }
                    }
                }, this);
            },
            changeType: function (event) {
                var element = $(Event.findElement(event, 'select'));
                if (element) {
                    this.setValue(element);
                }
            },
            setValues: function () {
                this.container.select('.type-select').forEach(function (typeField) {
                    if (!typeField.readAttribute('data-loaded')) {
                        typeField.writeAttribute('data-loaded', true);
                        this.setValue(typeField);
                    }
                }, this);
            },
            setValue: function (typeField) {
                var elementName = typeField.readAttribute('data-value');
                var input = jQuery("input[name='" + elementName + "']"),
                    select = jQuery("select[name='" + elementName + "']"),
                    values = [];
                switch (parseInt(typeField.getValue())) {
                    case 2:
                        values = [];
                        break;
                    case 8:
                    case 9:
                        values = config.dynamicAttributes;
                        break;
                    default:
                        values = config.attributes;
                }

                if (values.length) {
                    input.attr('disabled', 'disabled').hide();
                    select.removeAttr('disabled').show().find('option').remove();
                    select.append(jQuery("<option></option>")
                        .attr("value", "")
                        .text(translate("Not Set")));
                    values.forEach(function (data) {
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

            },
            setTitle: function (row_id) {
                var data = {},
                    element,
                    container = $('title-container-' + row_id);

                data.name = this.getTitleValue(row_id, 'name');
                if (!data.name) {
                    data.name = translate('New Row');
                }

                data.type = this.getTitleValue(row_id, 'type');
                data.value = this.getTitleValue(row_id, 'value');

                data.prefix_type = this.getTitleValue(row_id, 'prefix_type');
                data.prefix_value = this.getTitleValue(row_id, 'prefix_value');

                data.suffix_type = this.getTitleValue(row_id, 'suffix_type');
                data.suffix_value = this.getTitleValue(row_id, 'suffix_value');

                element = this.titleTemplate({
                    data: data
                });

                if( container ) {
                    container.innerHTML = '';
                    Element.insert(container, element);
                }

            },
            getTitleValue: function (row_id, name) {
                var input = jQuery("input[name='" + config.htmlName + "[" + row_id + "][" + name + "]']"),
                    select = jQuery("select[name='" + config.htmlName + "[" + row_id + "][" + name + "]']");
                if (!select.length || select.is(':disabled')) {
                    return input.val();
                }
                if (select.val()) {
                    return select.find('option:selected').text();
                }
                return '';
            },
            bindActions: function () {
                Event.observe('add_new_row_button', 'click', this.add.bind(Rows, {}));
                this.container.on('click', '.delete-row', this.remove.bind(this));
                this.container.on('click', '.fm-block-title', this.toggleEdit.bind(this));
                this.container.on('change', '.type-select', this.changeType.bind(this));
            },
            validateElement: function (element) {
                if (element.classList.contains('__opened')) {
                    jQuery('#edit_form').valid();
                    if (!element.select('.fm-block-title-right')[0].hasClassName('warning')) {
                        if (element.select('.mage-error').length > 0) {
                            var needToAdd = false;
                            for (var i = 0; i < element.select('.mage-error').length; i++) {
                                if (element.select('.mage-error')[i].getStyle('display') !== 'none') {
                                    needToAdd = true;
                                }
                            }
                            if (needToAdd === true) {
                                element.select('.fm-block-title-right')[0].addClassName('warning');
                            }
                        }
                    } else {
                        var needToDelete = true;
                        for (var i = 0; i < element.select('.mage-error').length; i++) {
                            if (element.select('.mage-error')[i].getStyle('display') !== 'none') {
                                needToDelete = false;
                            }
                        }
                        if (needToDelete === true) {
                            element.select('.fm-block-title-right')[0].removeClassName('warning');
                        }
                    }
                }
            }
        };
        Rows.init();
        jQuery(function ($) {
            $('[data-role=rows-container]').sortable({
                distance: 8,
                tolerance: 'pointer',
                cancel: 'input, button, select',
                axis: 'y',
                update: function () {
                    $('[data-role=rows-container] [data-role=order]').each(function (index, element) {
                        $(element).val(index + 1);
                    });
                }
            });
        });
    };
});
