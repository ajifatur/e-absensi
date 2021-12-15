<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/ashl1/datatables-rowsgroup@fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js"></script>
<script type="text/javascript">
    let DataTable = (selector, rowsGroup = null) => {
        let datatable = $(selector).DataTable({
            "language": {
                "lengthMenu": "Menampilkan _MENU_ data",
                "zeroRecords": "Data tidak tersedia",
                "info": "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
                "infoEmpty": "Data tidak ditemukan",
                "infoFiltered": "(Terfilter dari total _MAX_ data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "previous": "<",
                    "next": ">",
                },
                "processing": "Memproses data..."
            },
            // "fnDrawCallback": configFnDrawCallback,
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "Semua"]],
            "pageLength": 25,
            "rowsGroup": rowsGroup,
            "orderCellsTop": true,
            columnDefs: [
                {orderable: false, targets: 0},
                {orderable: false, targets: -1},
            ],
            order: []
        });
        return datatable;
    }
</script>