$(document).ready(() => {

    /*
       Click event for deleting the
       Rule
    */
    $(document).on('click', '.delete-rule', e => {
        e.preventDefault();
        let id = $(e.currentTarget).attr("data-attr");
        deleteRule(id);
    });

    // Pagination
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        var search = $('#search').val();

        if (search) {
            searchPagination(search, page);
        } else {
            simplePagination(page);

        }
    });

    /*
      Simple pagination in case of no search
    */
    function simplePagination(page) {
        $.ajax({
            url: "/rule-pagination",
            method: "Get",
            data: {
                page: page,
            },
            success: function(data) {
                $('#ruleTable').html(data);
            }
        });
    }

    /*
       Search pagination in case of search
    */
    function searchPagination(search, page) {

        $.ajax({
            url: "/rule-search",
            method: "Get",
            data: {
                page: page,
                search: search,
            },
            success: function(data) {
                $('#ruleTable').html(data);
            }
        });
    }

    /*
        Search the fields in the Rule table
        on key up event.
    */
    $(document).on('keyup', '#search', function() {

        var search = $('#search').val();

        $.ajax({
            type: 'Get',
            url: "/rule-search",
            data: {
                search: search,
            },
            success: function(data) {
                $('#ruleTable').html(data);
            },
        });
    });

    /*
       Funtion for Deleting the rule
    */
    function deleteRule(id) {
        let url = "/rule-delete";
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "id": id
            },
            success: function(response) {
                $('#ruleTable').html(response);
                $('.success-message-1').show();
                $("#message").html("Rule Deleted Successfully");

                $(".app-loader").hide();
                $("html, body").animate({
                    scrollTop: 0
                }, 700);
                setTimeout(() => {
                    $(".success-message-1").hide();
                }, 4000);
            },
            error: (reject) => {

                if (reject.status == 406) {
                    $(".exception-error").text(reject.status + " " + reject.responseJSON);
                    $(".error-message").show();
                }
                setTimeout(() => {
                    $(".error-message").hide();
                    $(".exception-error").text("Error");
                }, 4000);
                $(".app-loader").hide();
                $("html, body").animate({
                    scrollTop: 0
                }, 700);
                $(".app-loader").hide();
            }
        });
    }


    //Export Rules Data in Csv Format
    $("#exportCsv").click(() => {

        $('.app-loader').show();

        $.ajax({
            url: "/export-csv-of-rule",
            type: "GET",

            success: function(response) {

                $('.success-message-1').show();
                $('.success-message').html(`<div class="alert success"><dl><dt>Success</dt><dd>` + response + `! </dd></dl></div>`).show();

                $(".app-loader").hide();
                setTimeout(() => {
                    $(".success-message").hide();
                }, 4000);
            },
            error: function(response) {
                $(".app-loader").hide();
                setTimeout(() => {
                    $(".error-message").hide();
                    $(".exception-error").text("Error");
                }, 4000);
            }
        });
    });

});
