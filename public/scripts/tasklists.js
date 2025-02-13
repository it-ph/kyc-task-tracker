$(document).ready(function() {
    TASK.load();
});

const TASK = (() => {
    let this_task = {}

    // load data
    this_task.load = () => {
        var filter_status = $('#status').html();
        axios(`${APP_URL}/task?status=` + filter_status).then(function(response) {
            $('#tbl_task').DataTable().destroy();
            var table;
            console.log(response.data.data)
            response.data.data.forEach(val => {
                table +=
                    `<tr>
                        <td class="text-center">${val.status}</td>
                        <td>${val.employee_name}</td>
                        <td class="text-center">${val.shift_date}</td>
                        <td class="text-center">${val.date_received}</td>
                        <td>${val.cluster}</td>
                        <td>${val.client}</td>
                        <td>${val.role_activity}</td>
                        <td>${val.description}</td>
                        <td>${val.start_date}</td>
                        <td>${val.end_date}</td>
                        <td class="text-center">${val.date_completed}</td>
                        <td>${val.sla}</td>
                        <td class="text-center">${val.sla_missed}</td>
                        <td class="text-center">${val.actual_handling_time}</td>
                        <td class="text-center">${val.volume}</td>
                        <td>${val.remarks}</td>
                    </tr>`;
            });
            $('#tbl_task tbody').html(table)

            // $('#tbl_task thead tr:eq(1)  th:not( )').each(function(i) {
            //     $('input', this).on('keyup change', function() {
            //         if (table.column(i).search() !== this.value) {
            //             table
            //                 .column(i)
            //                 .search(this.value)
            //                 .draw();
            //         }
            //     });
            // });

            var table = $('#tbl_task').DataTable({
                language: {
                    oPaginate: {
                        sNext: '<i class="fa fa-forward"></i>',
                        sPrevious: '<i class="fa fa-backward"></i>',
                        sFirst: '<i class="fa fa-step-backward"></i>',
                        sLast: '<i class="fa fa-step-forward"></i>'
                    },
                },
                pagingType: "full_numbers",
                pageLength: 20,
                lengthMenu: [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100]
                ],
                order: [2, "desc"],
                columnDefs: [{ type: 'date', 'targets': [2] }],
                fixedColumns: {
                    left: 3
                },
                scrollX: true,
                bSortCellsTop: true
            });

            $('#loader').hide();
            if (response.data.data.length > 0)
                toastr.success(response.data.message);
            else
                toastr.info(response.data.message);
        }).catch(error => {
            toastr.error(null);
        });
    }

    return this_task;
})()