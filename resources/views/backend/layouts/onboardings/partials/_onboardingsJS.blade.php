<script>
document.addEventListener('DOMContentLoaded', function () {
    const dt = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        responsive: true,
        ajax: "{{ route('onboardings.index') }}",
        order: [[0, 'desc']],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
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

    // Create
    $('#create_submit').on('click', function () {
        $.ajax({
            url: "{{ route('onboardings.store') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                name: $('#create_name').val(),
                is_active: $('#create_is_active').val(),
            },
            success: function () {
                $('#createModal').modal('hide');
                $('#create_name').val('');
                dt.ajax.reload(null, false);
                successModal('SUCCESSFULLY CREATED');
            },
            error: function (xhr) {
                errorModal();
                console.error(xhr.responseText);
            }
        });
    });

    // Edit — load data
    $(document).on('click', '.editbtn', function () {
        const id = $(this).data('id');
        $.get("{{ url('/admin/onboardings') }}/" + id + "/edit", function (data) {
            $('#edit_id').val(data.id);
            $('#edit_name').val(data.name);
            $('#edit_is_active').val(data.is_active ? 1 : 0);
            $('#editModal').modal('show');
        });
    });

    // Edit — submit
    $('#edit_submit').on('click', function () {
        const id = $('#edit_id').val();
        $.ajax({
            url: "{{ url('/admin/onboardings') }}/" + id,
            type: 'PUT',
            data: {
                _token: "{{ csrf_token() }}",
                name: $('#edit_name').val(),
                is_active: $('#edit_is_active').val(),
            },
            success: function () {
                $('#editModal').modal('hide');
                dt.ajax.reload(null, false);
                successModal('SUCCESSFULLY UPDATED');
            },
            error: function (xhr) {
                errorModal();
                console.error(xhr.responseText);
            }
        });
    });

    // Status toggle
    $(document).on('click', '.change_status', function (e) {
        e.preventDefault();
        $('#status_id').val($(this).data('id'));
        $('#status_enabled').val($(this).data('enabled'));
        $('#status_title').text($(this).data('title'));
        $('#status_description').text($(this).data('description'));
    });

    $('#status_form').on('submit', function (e) {
        e.preventDefault();
        const id = $('#status_id').val();
        $.ajax({
            url: "{{ url('/admin/onboardings') }}/" + id + "/status",
            type: 'PATCH',
            data: {
                _token: "{{ csrf_token() }}",
                is_active: $('#status_enabled').val(),
            },
            success: function () {
                dt.ajax.reload(null, false);
                successModal('SUCCESSFULLY UPDATED');
            },
            error: function (xhr) {
                errorModal();
                console.error(xhr.responseText);
            }
        });
    });

    // Delete
    $(document).on('click', '.deletebtn', function (e) {
        e.preventDefault();
        $('#delete_id').val($(this).data('id'));
        $('#deletemodal').modal('show');
    });

    $('#delete_modal_clear').on('submit', function (e) {
        e.preventDefault();
        const id = $('#delete_id').val();
        $.ajax({
            url: "{{ url('/admin/onboardings') }}/" + id,
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