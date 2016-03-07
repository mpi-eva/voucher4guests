jQuery(function ($) {

    /* input mask with placeholder */
    //$.mask.definitions['~']='[a-zA-Z0-9]';
    //$('#voucher_code').mask("~~~~~-~~~~~-~~~~~-~~~~~",{placeholder:"_____-_____-_____-_____"});

    /* input mask without placeholder */
    $('#voucher_code').mask('ZZZZZ-ZZZZZ-ZZZZZ-ZZZZZ', {
        translation: {
            'Z': {
                pattern: /[a-zA-Z0-9]/,
                optional: false
            }
        }
    });
});