/*
 * This file is part of voucher4guests.
 *
 * voucher4guests Project - An open source captive portal system
 * Copyright (C) 2016. Alexander Müller, Lars Uhlemann
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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