var goMageFeedGenerate;

require([
    'jquery'
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
    }
});
