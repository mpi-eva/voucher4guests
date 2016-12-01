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

$('input[name="daterange"]').daterangepicker({
    opens: 'right',
    locale: {
        format: 'DD.MM.YYYY'
    },
});


// toogle button
$('.btn-group .btn').on('change',function(){

    var input1 = $("select[name='validity']").parent();
    var input2 = $("input[name='daterange']").parent().parent();

    if($(this).text()==$("#option1").parent().text()){
        input1.show();
        input2.hide();
    }
    if($(this).text()==$("#option2").parent().text()){
        input1.hide();
        input2.show();
    }
});

// enable / disable submit button
function checkInput() {
    var number = parseInt($("input[name='number']").val());
    var disable = true;

    if(!isNaN(number) && number > 0){

        if($("#option1").is(":checked")){

            var validity = $("select[name='validity']").val();

            if (validity != ''){
                var action = '?quantity='+number+'&validity='+validity;
                $("#create-form").attr("action", "pdf/create_pdf.php" + action);
                $('#create-btn').prop('disabled', false);
                disable = false;
            }
        }
        if($("#option2").is(":checked")){
            var daterange = $("input[name='daterange']").val();
            if (daterange != ''){

                var start = $("input[name='daterange']").data('daterangepicker').startDate.format('YYYY-MM-DD');
                var end = $("input[name='daterange']").data('daterangepicker').endDate.format('YYYY-MM-DD');
                var action = '?quantity='+number+'&validity=0&act_time='+start+'&exp_time='+end;
                $("#create-form").attr("action", "pdf/create_pdf.php" + action);
                $('#create-btn').prop('disabled', false);
                disable = false;
            }
        }

    }
    if (disable){
        $('#create-btn').prop('disabled', true);
    }
}

$("input[name='number']").keyup(function(){
    checkInput();
});
$("input[name='number']").on('change', function() {
    checkInput();
});
$("select[name='validity']").on('change', function() {
    checkInput();
});
$("input[name='daterange']").on('change', function() {
    checkInput();
});
$('.btn-group .btn').on('change',function(){
    checkInput();
});
$( document ).ready(function() {

    var input1 = $("select[name='validity']").parent();
    var input2 = $("input[name='daterange']").parent().parent();
    input1.show();
    input2.hide();

    checkInput();
});


