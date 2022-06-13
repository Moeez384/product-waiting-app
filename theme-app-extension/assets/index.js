$(document).ready(function() {

    $("#eo-sh-register-button").click((e) => {
        $("#eo-sh-login-div").hide();
        $("#eo-sh-register-div").show();
    });

    $("#eo-sh-login-button").click((e) => {
        $("#eo-sh-login-div").show();
        $("#eo-sh-register-div").hide();
    })

    $("#signup").click((e) => {
        e.preventDefault();
        $("#loginForm").hide();
        $("#signUpForm").show();
    });

    $("#login").click((e) => {
        e.preventDefault();
        $("#loginForm").show();
        $("#signUpForm").hide();
    });

    $("#customerLogin").click(() => {
        localStorage.setItem("flag", 1);
    });

    $("#eo-sh-login-form-button").click(() => {
        localStorage.setItem("flag", 1);
    });



    $("#checkout_url").val(window.location.href);

    var url = window.location.href;
    var baseURL = 'https://product-waiting-app.test/';
    var domain = Shopify.shop;
    var productUrl = url.substring(0, 50);
    var handle = $("a.product__title").attr("href").slice(10);

    url = url.substring(0, 57);

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

                        $(".eo-sh-waiting-list-button").show();
                        $(".eo-sh-waiting-list-button").text('you are in the waiting list');
                        $(".eo-sh-waiting-list-button").css({
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
                toastr.success(response);
                localStorage.removeItem('flag');
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
        $(".fa-spin").show();

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
                toastr.success(response);
                $(".eo-sh-form").find("input[type=password],input[type=email]").val("");
                toggleModal();
            },
            error: function(response) {

                if (response.responseJSON.errors) {
                    if (response.responseJSON.errors.email) {
                        $("#eo-sh-register-email-error ul li").show();
                        $("#eo-sh-register-email-error ul li").text(response.responseJSON.errors.email[0]);
                    }
                    if (response.responseJSON.errors.password[0]) {
                        $("#eo-sh-register-password-error ul li").show();
                        $("#eo-sh-register-password-error ul li").text(response.responseJSON.errors.password[0]);
                    }
                    setTimeout(() => {
                        $("#eo-sh-register-email-error ul li").hide();
                        $("#eo-sh-register-password-error ul li").hide();
                    }, 2000);
                }
                if (response.status == 404) {
                    console.log("hello")
                    toastr.error(response.responseJSON);
                }
                if (response.status == 444) {
                    toastr.error(response.responseJSON);
                }
            },
        });
    });

    function toggleModal() {
        modal.classList.toggle("show-modal");
        $(".fa-spin").css("display", "none");
    }

    $("#contactUsSubmit").click((e) => {
        e.preventDefault();
        let email = $("#contactEmail").val();

    });

});