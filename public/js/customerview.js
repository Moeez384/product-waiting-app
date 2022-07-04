$(document).ready(() => {

    // Edit the status of the Customer
    $(document).on('click', '.editCustomerStatus', function() {
        const currentRow = $(this).closest("tr");
        var id = $(this).data('id');
        $('.app-loader').show();

        $.ajax({
            url: "/change-status/" + id,
            type: "GET",
            success: function(response) {
                if (response.status == 0) {
                    currentRow.find(".status").html("Not Active");
                    currentRow.find(".statusHref a").html("Activate");
                } else {
                    currentRow.find(".status").html("Active");
                    currentRow.find(".statusHref a").html("Disable");
                }

                $('.success-message-1').show();
                $('.success-message').html(`<div class="alert success"><dl><dt>Success</dt><dd>` + response.message + `! </dd></dl></div>`).show();

                $(".app-loader").hide();
                setTimeout(() => {
                    $(".success-message").hide();
                }, 4000);
            },
            error: function(response) {
                if (response.status == 404) {
                    $(".exception-error").text('');
                    $(".exception-error").text(response.status + " " + response.responseJSON.error);
                    $(".error-message").show();
                }
                $(".app-loader").hide();
                setTimeout(() => {
                    $(".error-message").hide();
                    $(".exception-error").text("Error");
                }, 4000);
            }
        });
    });

    // Export Customer's Data in Csv Format
    $("#exportCsv").click(() => {

        $('.app-loader').show();

        $.ajax({
            url: "/export-csv-of-customers",
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


    // Pagination

    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        var search = $('#search').val();
        let productId = $("#searchByProduct option:selected").val();

        if (search != '' && productId == '') {
            searchPagination(search, page);
        } else if (search == '' && productId != '') {
            productIdPagination(productId, page);
        } else if (search != '' && productId != '') {
            searchAndProductPagination(search, productId, page);
        } else {
            simplePagination(page);
        }
    });

    /* This function is called when both
       fields have data and the user wants
       to apply pagination on the given
       data.
    */
    function searchAndProductPagination(search, productId, page) {
        $.ajax({
            type: 'Get',
            url: "/customer-search",
            data: {
                productId: productId,
                search: search,
                page: page,
            },
            success: function(data) {
                $('#customerTable').html(data);
            },
        });
    }

    /*   This function is called when only
         product field have data and the user
         wants to apply pagination according to
         that data.
    */
    function productIdPagination(productId, page) {
        $.ajax({
            type: 'Get',
            url: "/customer-search",
            data: {
                productId: productId,
                page: page,
            },
            success: function(data) {
                $('#customerTable').html(data);
            },
        });
    }

    /*  Search Based on product or
        any other field
    */

    $("#search").keyup(function() {
        var search = $('#search').val();
        let productId = $("#searchByProduct option:selected").val();
        if (productId == '') {
            searchByCustomer(search);
        } else if (productId) {
            searchByCustomerAndProductId(search, productId);
        }
    });

    function searchByCustomerAndProductId(search, productId) {
        $.ajax({
            type: 'Get',
            url: "/customer-search",
            data: {
                search: search,
                productId: productId,
            },
            success: function(data) {
                $('#customerTable').html(data);
            },
        });
    }

    $("#searchByProduct").change(() => {
        let productId = $("#searchByProduct option:selected").val();
        var search = $('#search').val();
        if (search == '') {
            searchByProductId(productId);
        } else if (search) {
            searchByCustomerAndProductId(search, productId);
        }
    });

    function searchByProductId(productId) {
        $.ajax({
            type: 'Get',
            url: "/customer-search",
            data: {
                productId: productId,
            },
            success: function(data) {
                $('#customerTable').html(data);
            },
        });
    }

    function simplePagination(page) {
        $.ajax({
            url: "/customers-index",
            method: "Get",
            data: {
                page: page,
            },
            success: function(data) {
                $("#pagination").empty();
                $('#customerTable').html(data);
            }
        });
    }

    function searchByCustomer(search) {
        $.ajax({
            type: 'Get',
            url: "/customer-search",
            data: {
                search: search,
            },
            success: function(data) {
                $('#customerTable').html(data);
            },
        });
    }

    function searchPagination(search, page) {

        $.ajax({
            type: 'Get',
            url: "/customer-search",
            data: {
                search: search,
                page: page,
            },
            success: function(data) {
                $('#customerTable').html(data);
            },
        });
    }
});
