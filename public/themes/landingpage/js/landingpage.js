$(function () {

    window.verifyRecaptchaCallback = function (response) {
        $('input[data-recaptcha]').val(response).trigger('change');
    }

    window.expiredRecaptchaCallback = function () {
        $('input[data-recaptcha]').val("").trigger('change');
    }

    $('#landingpage-form').validator();

    // $('#landingpage-form').on('submit', function (e) {
    //     if (!e.isDefaultPrevented()) {
    //         var url = "contact.php";

    //         $.ajax({
    //             type: "POST",
    //             url: url,
    //             data: $(this).serialize(),
    //             success: function (data) {
    //                 var messageAlert = 'alert-' + data.type;
    //                 var messageText = data.message;

    //                 var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
    //                 if (messageAlert && messageText) {
    //                     $('#landingpage-form').find('.messages').html(alertBox);
    //                     $('#landingpage-form')[0].reset();
    //                     grecaptcha.reset();
    //                 }
    //             }
    //         });
    //         return false;
    //     }
    // })
});