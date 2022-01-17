import Tagify from "@yaireo/tagify/dist/tagify.esm";

const auto = {
    checkBoxHandler: () => {
        $('#product_isPromo').on('change', function(){
            $('#product_promoDiscount').prop('required', $(this).is(':checked'));
        })
    },

    tagiFiedElement: () => {
        new Tagify(document.querySelector('.to-tagify'), {});
    }
}

$(function (){
    auto.checkBoxHandler();
    auto.tagiFiedElement();
})
