<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dt = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            responsive: true,
            ajax: "{{ route('salons.index') }}",
            order: [[0, 'desc']],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'location', name: 'location' },
                { data: 'address', name: 'address' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            language: {
                paginate: {
                    previous: '<i class="fas fa-angle-left"></i>',
                    next: '<i class="fas fa-angle-right"></i>'
                },
                processing: dataTableLoader()
            }
        });

        $(document).on('click', '.deletebtn', function (e) {
            e.preventDefault();
            $('#delete_id').val($(this).data('id'));
            $('#deletemodal').modal('show');
        });

        $('#delete_modal_clear').on('submit', function (e) {
            e.preventDefault();
            const id = $('#delete_id').val();
            $.ajax({
                url: "{{ url('/admin/salons') }}/" + id,
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function () {
                    dt.ajax.reload(null, false);
                    successModal('SUCCESSFULLY DELETED');
                },
                error: function (xhr) {
                    errorModal();
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>