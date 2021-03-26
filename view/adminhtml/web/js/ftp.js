define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    "jquery/ui",
], function ($, modal) {
    'use strict';

     function main (config) {
        var ajaxUrl = config.ajaxUrl;
        var isFtp = config.isFtp;
        var ftpProtocol = config.ftpProtocol;
        var ftpHost = config.ftpHost;
        var ftpPort = config.ftpPort;
        var ftpUser = config.ftpUser;
        var ftpPassword = config.ftpPassword;
        var ftpPassiveMode = config.ftpPassiveMode;
         $(document).on('click', '#ftp_connection', function(){
             var options = {
                 type: 'popup',
                 responsive: true,
                 innerScroll: true,
                 title: '',
                 buttons: [{
                     text: $.mage.__('OK'),
                     class: '',
                     click: function () {
                         this.closeModal();
                     }
                 }]
             };
                $.ajax({
                    showLoader: true,
                    context: '#ajaxresponse',
                    url: ajaxUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        isFtp:isFtp,
                        ftpProtocol:ftpProtocol,
                        ftpHost:ftpHost,
                        ftpPort:ftpPort,
                        ftpUser:ftpUser,
                        ftpPassword:ftpPassword,
                        ftpPassiveMode:ftpPassiveMode
                    },
                    success : function(data) {
                        if(data==null){
                            modal(options, $('#validation-fail-modal'));
                            $("#validation-fail-modal").modal("openModal");
                        }else{
                            if (data.error === true) {
                                modal(options, $('#connection-fail-modal'));
                                $("#connection-fail-modal").modal("openModal");
                            }else{
                                modal(options, $('#connection-success-modal'));
                                $("#connection-success-modal").modal("openModal");
                            }
                        }
                    }
                });
         });
    }
    return main;
});

