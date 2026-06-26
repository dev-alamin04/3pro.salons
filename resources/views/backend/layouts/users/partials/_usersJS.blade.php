<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dt = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            responsive: true,
            ajax: '{{ route('users.index') }}',
            order: [
                [0, 'desc']
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },

                {
                    data: 'joined_at',
                    name: 'joined_at'
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            language: {
                paginate: {
                    previous: '<i class="fas fa-angle-left"></i>',
                    next: '<i class="fas fa-angle-right"></i>'
                },
                processing: dataTableLoader()
            }
        });

        // Account status toggle
        $(document).on('click', '.change_status', function(e) {
            e.preventDefault();
            $('#status_id').val($(this).data('id'));
            $('#status_enabled').val($(this).data('enabled'));
            $('#status_title').text($(this).data('title'));
            $('#status_description').text($(this).data('description'));
        });

        $('#status_form').on('submit', function(e) {
            e.preventDefault();
            const id = $('#status_id').val();
            const isEnabled = $('#status_enabled').val();
            $.ajax({
                url: '/admin/users/' + id + '/account-status',
                type: 'PATCH',
                data: {
                    is_active: isEnabled
                },
                success: function(res) {
                    dt.ajax.reload(null, false);
                    successModal('SUCCESSFULLY UPDATED');
                },
                error: function(xhr) {
                    errorModal();
                    console.error(xhr.responseText);
                }
            });
        });



        // Delete user

        $(document).on('click', '.deletebtn', function(e) {
            e.preventDefault();
            $('#delete_id').val($(this).data('id'));
            $('#delete_type').val($(this).data('type'));
            $('#deletemodal').modal('show');
        });

        $('#delete_modal_clear').on('submit', function(e) {
            e.preventDefault();
            let id = $('#delete_id').val();
            $.ajax({
                url: '{{ url('/admin/users') }}/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    dt.ajax.reload(null, false);
                    successModal('SUCCESSFULLY DELETED');
                },
                error: function(xhr) {
                    errorModal();
                    console.error(xhr.responseText || xhr.statusText);
                }
            });
        });

    });
</script>
