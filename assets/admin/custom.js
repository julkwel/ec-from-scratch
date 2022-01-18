import Tagify from "@yaireo/tagify/dist/tagify.esm";
import {Dropzone} from "dropzone";

const auto = {
    checkBoxHandler: () => {
        let discountInput = $('#product_promoDiscount');
        let isPromoInput = $('#product_isPromo');
        isPromoInput.on('change', function(){
            discountInput.prop('required', $(this).is(':checked'));
            if (!$(this).is(':checked')){
                discountInput.val(0);
            }
        });
    },

    tagiFiedElement: () => {
        new Tagify(document.querySelector('.to-tagify'), {});
    },

    dropzone: () => {
        // new Dropzone("#add_image_to_product", {});
    }
}

$(function (){
    auto.checkBoxHandler();
    auto.tagiFiedElement();
    auto.dropzone();
})
