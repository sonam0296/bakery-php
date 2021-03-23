var Est = function () {

    var initComponents = function () {
        //init datepickers
        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            autoclose: true
        });
    }

    return {

        //main function to initiate the module
        init: function () {
            initComponents();
        }

    };

}();