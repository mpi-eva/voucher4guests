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


