<div class="text-center">
    <div class="d-flex justify-content-center gap-1" role="group">

        <!-- View Details -->
        @if (isset($show))
            <a href="{{ route($show, $id) }}"
               class="btn btn-sm rounded-pill px-3"
               style="background-color: #2a8dc7; color: #fff; border: none;"
               title="View Details">
                <i class="fas fa-eye"></i>
            </a>
        @endif

        <!-- Edit (Page) -->
        @if (isset($edit))
            <a href="{{ route($edit, $id) }}"
               class="btn btn-sm rounded-pill px-3"
               style="background-color: #352ace; color: #fff; border: none;"
               title="Edit">
                <i class="fas fa-edit"></i>
            </a>
        @endif

        <!-- Edit (Modal) -->
        @if (isset($editModal))
            <a href="#"
               class="btn btn-sm rounded-pill px-3 editbtn"
               style="background-color: #2a8dc7; color: #fff; border: none;"
               title="Edit"
               data-id="{{ $id }}">
                <i class="fas fa-edit"></i>
            </a>
        @endif

        <!-- Delete -->
        @if (isset($delete) && $delete)
            <a href="#"
               class="btn btn-sm rounded-pill px-3 deletebtn"
               style="background-color: #dc3545; color: #fff; border: none;"
               title="Delete"
               data-id="{{ $id }}">
                <i class="fas fa-trash-alt"></i>
            </a>
        @endif

    </div>
</div>