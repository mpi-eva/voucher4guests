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


/* Formatting function for row details - modify as you need */
function format ( d ) {
    // `d` is the original data object for the row

    var s = '';
    if(d.macs !== undefined) {
        s +='<table cellpadding="0" cellspacing="0" border="0" class="innerTable">';
        s +=
            '<tr>'+
            '<th>MAC-Adresse</th>'+
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
    return s;
}

$(document).ready(function() {
    var table = $('#database_table').DataTable( {
        "ajax": "includes/voucher_data.php",
        "columns": [
            {
               // "className":      'dtails-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "vid" },
            { "data": "voucher_code" },
            { "data": "validity" },
            { "data": "status" },
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
        "order": [[1, 'asc']],
        "lengthMenu": [[50, 100, 200, 400, -1], [50, 100, 200, 400, "All"]],
        "scroller":       true,
        "deferRender":    true,
        "scrollY":        400,
        "paging":         true,
        "scrollCollapse": true,
        "stateSave":      true
    } );

    // Add event listener for opening and closing details
    $('#database_table tbody').on('click', 'td.details-control', function () {
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

    // show modal
    $('#functionModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var option = button.data('option');
        var label = button.data('label');
        var title = button.html();

        var modal = $(this);
        modal.find('.modal-title').text(title);
        modal.find('.modal-body label').html(label);
        var submit =  modal.find('#submit-data');
        submit.html(title);

        submit.off('click').on('click', function () {
            var data = modal.find('.modal-body input').val();
            deactivate(option, data);
            modal.find('.modal-body input').val("");
            modal.modal('hide');
        });
    });


    function deactivate(option, data){
        $.ajax({
            'url':'includes/deactivate.php',
            'type':'POST',
            'async':false,
            'data':{ 'option' : option,
                     'data' : data
            },
            error: function (request, status, error) {
                alert("ERROR with ajax function");
            },
            'success':function(data){

                var modal= $('#infoModal');
                modal.find('.modal-body p').html(data);
                modal.modal('show');
                table.ajax.reload(null, false);
            }
        });
    }



});

