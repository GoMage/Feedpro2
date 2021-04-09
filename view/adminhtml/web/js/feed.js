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
 * @version      Release: 1.4.0
 * @since        Class available since Release 1.0.0
 */

var goMageFeedGenerate;

require([
    'jquery',
    'jquery/validate',
    'mage/translate'
], function ($) {
    'use strict';

    var goMageFeed = {
        forceStop: false,
        progressByPage: function (url, page) {
            var self = this;

            $.ajax({
                'url': url,
                'method': 'POST',
                'data': {
                    'page': page
                }
            }).done(function (data) {
                if (data.error === true) {
                    $('#goMageFeedProgressModal').dialog('close');
                    $('.page-main-actions').after(self.getMessagesHtml('error', data.message));

                    return;
                }

                var percent = ((data.currentPage * 100) / data.totalPages);
                $('#goMageFeedProgressBar').progressbar({
                    value: percent
                });

                $('#goMageFeedProgressValue').html(Math.floor(percent));

                page++;
                if (page <= data.totalPages && !self.forceStop) {
                    self.progressByPage(url, page);
                } else {
                    $('#goMageFeedProgressModal').dialog('close');
                    $('#goMageFeedProgressValue').html(0);

                    jQuery('.field-generated_at .control-value').text(data.lastGenerated);
                    jQuery('.field-generation_time .control-value').text(data.generationTime);
                    jQuery('.field-comments .control-value a').text(data.url);

                    $('.page-main-actions').after(self.getMessagesHtml('success', data.message));
                }
            });
        },
        generate: function (url) {
            var self = this;
            self.forceStop = false;

            $("#goMageFeedProgressModal").dialog({
                modal: true,
                closeOnEscape: false,
                dialogClass: 'gomage-feed-progress-modal',
                buttons: {
                    Stop: function () {
                        self.forceStop = true;
                        $(this).dialog("close");
                    }
                }
            });

            $("#goMageFeedProgressBar").progressbar({
                value: 0
            });

            self.progressByPage(url, 1);
        },
        getMessagesHtml: function (messageType, message) {
            return '<div id="messages">' +
                        '<div class="messages">' +
                            '<div class="message message-' + messageType + ' ' + messageType + '>' +
                                '<div data-ui-id="messages-message-' + messageType + '">' +
                                    message +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>'
        }
    };

    goMageFeedGenerate = function (url) {
        goMageFeed.generate(url);
    };

    $.validator.addMethod(
        'validate-no-spaces', function (value) {
            return value.indexOf(' ') === -1;
        }, $.mage.__('Spaces are not allowed in the file name!'));
});
