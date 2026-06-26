<div class="modal fade bd-example-modal-sm" id="deletemodal" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content modal_icon">
            <form action="" method="post" id="delete_modal_clear">
                @csrf
                <div class="modal-title" id="exampleModalLongTitle">
                    <input type="hidden" name="delete_id" id="delete_id">
                    <input type="hidden" name="delete_type" id="delete_type">
                    <div align="middle" style="padding: 10px;">
                        <!-- <img src="{{ asset('backend/assets/icon/delete-icon.png') }}" alt=""> -->
                         <i class="fa-regular fa-trash-can fa-2xl" style="color: aliceblue; margin-top: auto;"></i>
                    </div>
                    <br> 
                    <h5 style="color: white">Do you want to Delete?</h5>
                    <div></div>
                    <div class="text-muted">You won't be able to recover it!!</div>
                    <br>
                </div>

                <div>

                    <button type="button" class="btn btn-secondary btn-sm" style="margin:0px 10px"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger delete_data btn-sm" data-bs-dismiss="modal"
                        style="margin:0px 10px">Delete</button>

                </div>
            </form>

        </div>
    </div>
</div>
