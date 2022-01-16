const auto = {
    checkBoxHandler: () => {
        $('#product_isPromo').on('change', function(){
            $('#product_promoDiscount').prop('required', $(this).is(':checked'));
        })
    }
}

$(function (){
    auto.checkBoxHandler();
})
