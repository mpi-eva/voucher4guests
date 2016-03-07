$('input[name="daterange"]').daterangepicker({
    opens: 'right',
    locale: {
        format: 'DD.MM.YYYY'
    },
});


// toogle button
$('.btn-group .btn').on('change','click',function(){

    var input1 = $("select[name='validity']");
    var input2 = $("input[name='daterange']");

    var disabled_txt_color = '#888';
    var enabled_txt_color = '#333';


    if($(this).text()==$("#option1").parent().text()){

        input1.prop('disabled', false);
        input1.parent().find("label").css("color", enabled_txt_color);

        input2.prop('disabled', true);
        input2.parent().parent().find("label").css("color", disabled_txt_color);

    }
    if($(this).text()==$("#option2").parent().text()){

        input2.prop('disabled', false);
        input2.parent().parent().find("label").css("color", enabled_txt_color);

        input1.prop('disabled', true);
        input1.parent().find("label").css("color", disabled_txt_color);
    }
})

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
    alert('test');
    checkInput();
});


