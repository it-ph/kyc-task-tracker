<div class="modal fade" id="addActivityModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="addActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addActivityModalLabel">Add New Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="storeActivityForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="role_id" value="{{ $role_id }}">
                        <label for="name" class="col-form-label custom-label"><strong>ACTIVITY NAME:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="name">
                        <label id="nameError" class="error" style="display:none"></label>
                    </div>
                    <div class="form-group">
                        <label for="sla" class="col-form-label custom-label"><strong>SLA (hours):<span class="important">*</span></strong></label>
                        <input type="string" class="form-control" name="sla">
                        <label id="slaError" class="error" style="display:none"></label>
                    </div>
                    {{-- <div class="form-group">
                        <label for="frequency" class="col-form-label custom-label"><strong>FREQUENCY:<span class="important">*</span></strong></label>
                        <select name="frequency" class="form-control">
                            <option value="">-- Select Frequency --</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                        <label id="frequencyError" class="error" style="display:none"></label>
                    </div>
                    <div class="form-group">
                        <label for="schedule" class="col-form-label custom-label"><strong>SCHEDULE:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="schedule">
                        <label id="scheduleError" class="error" style="display:none"></label>
                    </div>
                    <div class="form-group">
                        <label for="function" class="col-form-label custom-label"><strong>FUNCTION:<span class="important">*</span></strong></label>
                        <select name="function" class="form-control">
                            <option value="">-- Select Function --</option>
                            <option value="P2P">P2P</option>
                            <option value="O2C">O2C</option>
                            <option value="RTR">RTR</option>
                            <option value="Admin">Admin</option>
                        </select>
                        <label id="functionError" class="error" style="display:none"></label>
                    </div> --}}
            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_save" class="btn btn-primary waves-effect waves-light"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
