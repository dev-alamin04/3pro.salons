{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Onboarding</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" id="create_name" class="form-control" placeholder="Enter name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select id="create_is_active" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="create_submit">Create</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Onboarding</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" id="edit_name" class="form-control" placeholder="Enter name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select id="edit_is_active" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="edit_submit">Update</button>
            </div>
        </div>
    </div>
</div>