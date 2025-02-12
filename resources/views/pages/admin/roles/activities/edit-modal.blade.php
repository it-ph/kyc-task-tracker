<div class="modal fade" id="editActivityModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editActivityModalLabel">Add New Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editActivityForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>ACTIVITY NAME:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="name" id="name_edit" required>
                        <label id="nameError" class="error" style="display:none"></label>
                    </div>
                    <div class="form-group">
                        <label for="sla" class="col-form-label custom-label"><strong>SLA:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="sla" id="sla_edit" required>
                        <label id="slaError" class="error" style="display:none"></label>
                    </div>
                    {{-- <div class="form-group">
                        <label for="frequency" class="col-form-label custom-label"><strong>FREQUENCY:<span class="important">*</span></strong></label>
                        <select name="frequency" id="frequency_edit" class="form-control">
                            <option value="">-- Select Frequency --</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                        <label id="frequencyError" class="error" style="display:none"></label>
                    </div>
                    <div class="form-group">
                        <label for="schedule" class="col-form-label custom-label"><strong>SCHEDULE:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="schedule" id="schedule_edit" required>
                        <label id="scheduleError" class="error" style="display:none"></label>
                    </div>
                    <div class="form-group">
                        <label for="function" class="col-form-label custom-label"><strong>FUNCTION:<span class="important">*</span></strong></label>
                        <select name="function" id="function_edit" class="form-control">
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
                    <button type="submit" id="btn_update" class="btn btn-primary waves-effect waves-light"><i class="fa fa-save"></i> Update</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                    </form>
                </div>
        </div>
    </div>
</div>
