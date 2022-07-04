$(document).ready(function() {

    $("#checkout_url").val(window.location.href);

    var url = window.location.href;
    var baseURL = 'https://product-waiting-app.test/';
    var domain = Shopify.shop;
    var productUrl = url.substring(0, 50);
    var handle = $("a.product__title").attr("href").slice(10);

    url = url.substring(0, 57);


    $("#eo-sh-login-heading").click((e) => {
        e.preventDefault();
        $(".eo-sh-register-div").hide();
        $(".eo-sh-login-div").show();

        $(".login-register .register").css({
            "background-color": "#E0EDF3",
            "border": "1px solid #E0EDF3",
        });

        $(".login-register .login").css({
            "background-color": "#069CE2",
            "border": "1px solid #069CE2",
        });
    });

    $("#eo-sh-register-heading").click((e) => {
        e.preventDefault();
        $(".eo-sh-login-div").hide();
        $(".eo-sh-register-div").show();

        $(".login-register .register").css({
            "background-color": "#069CE2",
            "border": "1px solid #069CE2",
        });

        $(".login-register .login").css({
            "background-color": "#E0EDF3",
            "border": "1px solid #E0EDF3",
        });
    });

    $("#eo-sh-login-form-button").click(() => {
        localStorage.setItem("flag", 1);
    });

    if (productUrl == "https://product-waiting-app.myshopify.com/products") {
        $(".app-loader").show();
        $.ajax({
            url: baseURL + 'api/check-product-handle',
            type: 'GET',
            async: false,
            crossDomain: true,
            data: {
                'domain_name': domain,
                'handle': handle,
                'cid': __st.cid,
            },
            contentType: "json",
            dataType: "json",
            success: function(response) {

                if (response.tocken == 2) {
                    if (response.customer.status == '0') {
                        $(".product-form__submit").hide();
                        $(".shopify-payment-button").hide();

                        $(".eo-sh-waiting-list-button").hide();
                        $(".eo-sh-message-button").show();
                        $(".eo-sh-message-button").text(response.button_message);
                        $(".eo-sh-message-button").css({
                            "color": response.setting.waiting_list_button_text_color,
                            "background-color": response.setting.waiting_list_button_bg_color,
                            "width": "75%",
                            "padding": "15px 0px",
                            "border": "none",
                        });
                    } else if (response.customer.status == '1') {}
                } else if (response.token == 1) {
                    $(".product-form__submit").hide();
                    $(".shopify-payment-button").hide();

                    $(".eo-sh-waiting-list-button").show();
                    $(".eo-sh-waiting-list-button").text(response.setting.waiting_list_button_text);
                    $(".eo-sh-waiting-list-button").css({
                        "color": response.setting.waiting_list_button_text_color,
                        "background-color": response.setting.waiting_list_button_bg_color,
                        "width": "75%",
                        "padding": "15px 0px",
                        "border": "none",
                    });
                } else if (response.token == 0) {
                    $(".product-form__submit").hide();
                    $(".shopify-payment-button").hide();

                    $(".eo-sh-waiting-list-button").show();
                    $(".eo-sh-waiting-list-button").text('Product is out of stock');
                    $(".eo-sh-waiting-list-button").css({
                        "color": response.setting.waiting_list_button_text_color,
                        "background-color": response.setting.waiting_list_button_bg_color,
                        "width": "75%",
                        "padding": "15px 0px",
                        "border": "none",
                    });
                }
            },
            error: function(response) {
                console.log(response);
            },
        });
    }

    if (localStorage.getItem("flag") == 1 && __st.cid) {

        $.ajax({
            url: baseURL + 'api/login-customer',
            type: 'GET',
            async: false,
            crossDomain: true,
            data: {
                'domain_name': domain,
                'cid': __st.cid,
                'handle': handle,
            },
            contentType: "json",
            dataType: "json",
            success: function(response) {
                // toastr.success(response);
                localStorage.removeItem('flag');

                $("#eo-sh-success-message-div").show();
                $(".waiting-list p").text(response.success_message);

                setTimeout(() => {
                    $("#eo-sh-success-message-div").fadeOut();
                }, 4000);
            },
            error: function(response) {
                if (response.status == 404) {
                    toastr.error(response.responseJSON);
                }
                localStorage.removeItem('flag');
            },
        });
    }


    $("#eo-sh-register-form-button").off().click((e) => {

        e.preventDefault();
        $("#eo-sh-spiner").addClass("fa-spinner");
        $("#eo-sh-spiner").addClass("fa-spin");

        let email = $("#eo-sh-register-email").val();
        let password = $("#eo-sh-register-password").val();

        $.ajax({
            url: baseURL + 'api/register-customer',
            type: 'GET',
            async: false,
            crossDomain: true,
            data: {
                'domain_name': domain,
                'email': email,
                'password': password,
                'handle': handle,
            },
            contentType: "json",
            dataType: "json",
            success: function(response) {
                // toastr.success(response);
                $(".eo-sh-form").find("input[type=password],input[type=email]").val("");
                toggleModal();
                $("#eo-sh-success-message-div").show();
                $(".waiting-list p").text(response.success_message);

                setTimeout(() => {
                    $("#eo-sh-success-message-div").fadeOut();
                }, 4000);

                $(".eo-sh-waiting-list-button").hide();
                $(".eo-sh-message-button").show();
                $(".eo-sh-message-button").text(response.button_message);
                $(".eo-sh-message-button").css({
                    "color": response.setting.waiting_list_button_text_color,
                    "background-color": response.setting.waiting_list_button_bg_color,
                    "width": "75%",
                    "padding": "15px 0px",
                    "border": "none",
                });
            },
            error: function(response) {
                if (response.responseJSON.errors) {
                    if (response.responseJSON.errors.email) {
                        $("#eo-sh-register-email-error").show();
                        $("#eo-sh-register-email-error").text(response.responseJSON.errors.email[0]);
                    }
                    if (response.responseJSON.errors.password[0]) {
                        $("#eo-sh-register-password-error").show();
                        $("#eo-sh-register-password-error").text(response.responseJSON.errors.password[0]);
                    }
                    setTimeout(() => {
                        $("#eo-sh-register-email-error").hide();
                        $("#eo-sh-register-password-error").hide();
                    }, 4000);
                }
                if (response.status == 404) {
                    toastr.error(response.responseJSON);
                    $("#eo-sh-register-email").val('');
                    $("#eo-sh-register-password").val('');
                }
                if (response.status == 444) {
                    toastr.error(response.responseJSON);
                    $("#eo-sh-register-email").val('');
                    $("#eo-sh-register-password").val('');
                }
            },
        });
    });

    function toggleModal() {
        modal.classList.toggle("show-modal");
    }

    $("#contactUsSubmit").click((e) => {
        e.preventDefault();
        let email = $("#contactEmail").val();
    });

    $(".eo-sh-close-modal-button").click((e) => {
        e.preventDefault();
        toggleModal();
    });

});
