$(document).ready(() => {
    $(document).on('click', '.user-guide, #back, .general-settings, .home, .orders-home, .add-rule, .edit-rule, .view-orderDetails, .delete-order, .button-plan, .plan-usage, .button-plan, .add-order, .report-orders, .select-plan', () => {
        $(".app-loader").show();
    });
});
