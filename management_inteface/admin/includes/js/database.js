/* Formatting function for row details - modify as you need */
function format ( d ) {
    // `d` is the original data object for the row

    var s = '';
    if(d.macs !== undefined) {
        s +='<table cellpadding="0" cellspacing="0" border="0" class="innerTable">';
        s +=
            '<tr>'+
            '<th>MAC</th>'+
            '<th>aktiv</th>'+
            '<th>Aktivierung</th>'+
            '<th>Deaktivierung</th>'+
            '</tr>';

        for(var key in d.macs){
            s +=
                '<tr>'+
                '<td>'+d.macs[key].mac_address+'</td>'+
                '<td>'+d.macs[key].active+'</td>'+
                '<td>'+d.macs[key].activation_time+'</td>'+
                '<td>'+d.macs[key].deactivation_time+'</td>'+
                '</tr>';
        }

        s+= '</table>';
    }
    /*var s ='<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
        '<td>Full name:</td>'+
        '<td>'+d.vid+'</td>'+
        '</tr>'+
        '<tr>'+
        '<td>Extension number:</td>'+
        '<td>'+d.voucher_code+'</td>'+
        '</tr>'+
        '<tr>'+
        '<td>Extra info:</td>'+
        '<td>And any further details here (images etc)...</td>'+
        '</tr>'+
        '</table>';*/


    return s;
}

$(document).ready(function() {
       var table = $('#example').DataTable( {
       // "ajax": "../ajax/data/objects.txt",
       // "ajax": "includes/js/data.json",
        "ajax": "includes/Data.php",
        "columns": [
            {
                "className":      'dtails-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "vid" },
            { "data": "voucher_code" },
            { "data": "validity" },
            { "data": "active" },
            { "data": "activation_time" },
            { "data": "expiration_time" },
            { "data": "use_by_date" },
            { "data": "macs" },
            { "data": "macs" }
        ],
       "createdRow": function ( row, data, index ) {

            if(data.macs !== undefined)  {
                   $('td', row).eq(0).addClass('details-control');
               }
       },
        "columnDefs": [
            {
                "render": function ( data, type, row ) {
                    var s = '';
                    for(var key in data){
                       s+= data[key].mac_address+', ';
                    }

                    return s;
                },
                "targets": [9]
            },
            {
                "render": function ( data, type, row ) {
                    if(data !== undefined) {
                        return data.length;
                    }else{
                        return 0;
                    }

                },
                "targets": [8]
            },
           {
                "visible": false,
                "targets": [9],
            }
        ],
        "order": [[1, 'asc']]
    } );

    // Add event listener for opening and closing details
    $('#example tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );
} );