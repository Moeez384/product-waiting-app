$(document).ready(() => {

    //Jquery Date Picker.
    $(".datepicker").datepicker();

    //Select2
    $(".myselect").select2([]);

    /* Changing the status in case of
       click event on the check box.
     */
    $("#status_cb").on('click', () => {
        if ($("#status").val() == 1) {
            $("#status").val(0);
        } else if ($("#status").val() == 0) {
            $("#status").val(1);
        }
    });

    /* Retrive all product from the shopify
       product api using select2 ajax call.
    */

    $('#product').select2({
        ajax: {
            url: "/product-get",
            data: function(params) {
                var query = {
                    term: params.term,
                    type: 'public'
                }
                return query;
            },
            processResults: function(data) {
                return {
                    results: data,
                };
            },
        }
    });


    /*
        On save button click event call the
        saveRuleData function to save rule data
        in the data base.
    */
    $('.btn-save').on('click', e => {
        e.preventDefault();

        saveRuleData();
    });

    /*
        Saving the Rule Data in the Database
        using the ajax call.
    */
    function saveRuleData() {
        let data = $('#product-rule-settings').serialize();

        const url = "rules-save";
        $(".app-loader").show();
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function(response) {

                $('.success-message-1').show();
                $('.success-message').html(`<div class="alert success"><dl><dt>Success</dt><dd>` + response + `! </dd></dl></div>`).show();

                $(".app-loader").hide();
                $("html, body").animate({
                    scrollTop: 0
                }, 700);
                setTimeout(() => {
                    $(".success-message").hide();
                }, 4000);
            },
            error: function(reject) {

                if (reject.responseJSON.errors) {

                    if (reject.responseJSON.errors.title) {
                        $("#titleError").show()
                        $("#titleError").text(reject.responseJSON.errors.title[0]);
                    }
                    if (reject.responseJSON.errors.no_of_customers) {
                        $("#noOfCutomerError").show()
                        $("#noOfCutomerError").text(reject.responseJSON.errors.no_of_customers[0]);
                    }
                    if (reject.responseJSON.errors.start_date) {
                        $("#startDateError").show()
                        $("#startDateError").text(reject.responseJSON.errors.start_date[0]);
                    }
                    if (reject.responseJSON.errors.end_date) {
                        $("#endDateError").show()
                        $("#endDateError").text(reject.responseJSON.errors.end_date[0]);
                    }
                    if (reject.responseJSON.errors.products) {
                        $("#productError").show()
                        $("#productError").text(reject.responseJSON.errors.products[0]);
                    }
                }

                if (reject.status == 406) {
                    $(".exception-error").text(reject.status + " " + reject.responseJSON);
                    $(".error-message").show();
                } else {
                    $(".exception-error").text(reject.status + " " + reject.responseJSON.message);
                    $(".error-message").show();
                }
                setTimeout(() => {
                    $(".error-message").hide();
                    $(".exception-error").text("Error");
                }, 4000);
                $("html, body").animate({
                    scrollTop: 0
                }, 700);
                $(".app-loader").hide();
                $(".btn-save").prop("disabled", false);
                setTimeout(() => {
                    $(".fieldErrors").hide();
                }, 4000);
            }
        });
    }
});