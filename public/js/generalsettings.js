var errorMessages = [];

$(document).ready(() => {
    $("#enable_app_cb").on('click', () => {
        if ($("#enable_app").val() == 1) {
            $("#enable_app").val(0);
        } else if ($("#enable_app").val() == 0) {
            $("#enable_app").val(1);
        }
    });

    $('.btn-save').on('click', e => {
        e.preventDefault();
        $('.success-message-1').fadeOut('slow');
        $(".btn-save").prop("disabled", true);
        for (let index = 0; index < errorMessages.length; index++) {
            errorMessages[index].next().children().remove();
            errorMessages[index].parent().removeClass('error');
        }
        errorMessages.splice(0, errorMessages.length);
        savingData();
    });
});

function savingData() {
    let data = $('#form_settings').serialize();
    const url = "/general-settings-save";
    $(".app-loader").show();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(response) {
            $('.success-message-1').show();
            $('.success-message').html(`<div class="alert success"><dl><dt>Success</dt><dd>` + response + `! </dd></dl></div>`).show();

            $("html, body").animate({
                scrollTop: 0
            }, 700);
            $(".app-loader").hide();
            setTimeout(() => {
                $(".success-message").hide();
            }, 4000);
        },
        error: function(reject) {
            for (let index in reject['responseJSON']['errors']) {
                $('.error_' + index).append('<label class="error">' + reject['responseJSON']['errors'][index][0] + "</label>");
                $('#' + index).parent().addClass('error');
                errorMessages.push($('#' + index));
                $('#' + index).focus(() => {
                    $(this).next().children().remove();
                    $(this).parent().removeClass('error');
                });
            }
            $(".exception-error").text(reject.status + " " + reject.responseJSON.message);
            $(".error-message").show();
            setTimeout(() => {
                $(".error-message").hide();
                $(".exception-error").text("Error");
            }, 3000);
            $("html, body").animate({
                scrollTop: 0
            }, 700);
            $(".app-loader").hide();
            $(".btn-save").prop("disabled", false);
        }
    });
}