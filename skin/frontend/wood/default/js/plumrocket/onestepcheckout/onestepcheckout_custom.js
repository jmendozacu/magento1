/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
(function ($) {
    $(document).ready(function () {
        $('.control').on('click', function () {
            updateHeightBody();
        });
    });
})(jQuery);

function updateHeightBody() {
    if (jQuery('.checkout-onepage-index').length > 0) {
        if (jQuery('#shipping-new-address-form').css('display') === 'none') {
            jQuery('.checkout-onepage-index .main-container').css('padding-bottom', '300px');
        }
        else {
            jQuery('.checkout-onepage-index .main-container').css('padding-bottom', '700px');
        }
    }
}
