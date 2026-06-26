<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dt = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            responsive: true,
            ajax: '{{ route('salons.assign', $salon->id) }}',
            order: [[0, 'desc']],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
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

        // Assign user
        $('#assign_btn').on('click', function () {
            const userId = $('#user_select').val();
            if (!userId) return errorModal('Please select a user.');

            $.ajax({
                url: '{{ route('salons.assign.user', $salon->id) }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', user_id: userId },
                success: function () {
                    dt.ajax.reload(null, false);
                    successModal('USER ASSIGNED SUCCESSFULLY');
                    $('#user_select').val('');
                },
                error: function (xhr) {
                    const msg = xhr.responseJSON?.message ?? 'Something went wrong.';
                    errorModal(msg);
                }
            });
        });

        // Remove user
        $(document).on('click', '.remove-user', function () {
            const salonId = $(this).data('salon');
            const userId  = $(this).data('user');

            $.ajax({
                url: '/admin/salons/' + salonId + '/users/' + userId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function () {
                    dt.ajax.reload(null, false);
                    successModal('USER REMOVED SUCCESSFULLY');
                },
                error: function (xhr) {
                    errorModal();
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>