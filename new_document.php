<?php if (!isset($conn)) {
	include 'db_connect.php';
} ?>

<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="manage-project">

				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

				<!--------------------- ROW 1 --------------------->
				<div class="row">
					<div class="col-md-9">
						<div class="form-group">
							<label class="control-label">Particulars</label>
							<input type="text" class="form-control form-control-sm" autocomplete="off" name="particulars" value="<?= htmlspecialchars($particulars ?? '', ENT_QUOTES) ?>">
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">PR No.</label>
							<input type="text" class="form-control form-control-sm" autocomplete="off" name="pr_no" value="<?= htmlspecialchars($pr_no ?? '', ENT_QUOTES) ?>">
						</div>
					</div>

				</div>
				<!--------------------- ROW 2 --------------------->
				<div class="row pb-3">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Amount</label>
							<input type="text" class="form-control form-control-sm" autocomplete="off" name="amount" value="<?= htmlspecialchars($amount ?? '', ENT_QUOTES) ?>">
						</div>
					</div>
	
					<div class="col-md-3">
						<div class="form-group">
							<label>Status</label>
							<select name="status" id="status" class="custom-select custom-select-sm">
								<option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>Pending</option>
								<option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>Started</option>
								<option value="2" <?= isset($status) && $status == 2 ? 'selected' : '' ?>>On-Progress</option>
								<option value="3" <?= isset($status) && $status == 3 ? 'selected' : '' ?>>On-Hold</option>
								<option value="4" <?= isset($status) && $status == 4 ? 'selected' : '' ?>>Overdue</option>
								<option value="5" <?= isset($status) && $status == 5 ? 'selected' : '' ?>>Done</option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="start_date" class="control-label">Start Date</label>
							<input type="date"
								   id="start_date"
								   name="start_date"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   value="<?= isset($start_date) && $start_date !== '' ? htmlspecialchars(date('Y-m-d', strtotime($start_date)), ENT_QUOTES) : '' ?>">
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="end_date" class="control-label">Target End Date</label>
							<input type="date"
								   id="end_date"
								   name="end_date"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   value="<?= isset($end_date) && $end_date !== '' ? htmlspecialchars(date('Y-m-d', strtotime($end_date)), ENT_QUOTES) : '' ?>">
						</div>
					</div>
				</div>
				<!--------------------- ROW 3 --------------------->
				<div class="row border-top pt-3">
					<div class="col-md-3">
						<div class="form-group">
							<label for="mop">MOP</label>
							<select name="mop" id="mop" class="custom-select custom-select-sm">
								<option value="" <?= !isset($mop) || $mop === '' ? 'selected' : '' ?>>No Data Available</option>
								<option value="svp" <?= isset($mop) && $mop === 'svp' ? 'selected' : '' ?>>SVP</option>
								<option value="lease" <?= isset($mop) && $mop === 'lease' ? 'selected' : '' ?>>LEASE OF VENUE</option>
								<option value="repeat" <?= isset($mop) && $mop === 'repeat' ? 'selected' : '' ?>>REPEAT ORDER</option>
								<option value="a_to_a" <?= isset($mop) && $mop === 'a_to_a' ? 'selected' : '' ?>>A-TO-A</option>
								<option value="direct" <?= isset($mop) && $mop === 'direct' ? 'selected' : '' ?>>DIRECT</option>
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="received_bac_first">received by BAC from BO</label>
							<input type="date"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   name="received_bac_first"
								   id="received_bac_first"
								   value="<?= isset($received_bac_first) && $received_bac_first !== '' ? htmlspecialchars(date('Y-m-d', strtotime($received_bac_first)), ENT_QUOTES) : '' ?>">
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="received_gso_first">received by GSO</label>
							<input type="date"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   name="received_gso_first"
								   id="received_gso_first"
								   value="<?= isset($received_gso_first) && $received_gso_first !== '' ? htmlspecialchars(date('Y-m-d', strtotime($received_gso_first)), ENT_QUOTES) : '' ?>">
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="procurement_type">Type of Procurement</label>
							<select name="procurement_type" id="procurement_type" class="custom-select custom-select-sm">
								<option value="" <?= !isset($procurement_type) || $procurement_type === '' ? 'selected' : '' ?>>No Data Available</option>
								<option value="single" <?= isset($procurement_type) && $procurement_type === 'single' ? 'selected' : '' ?>>Single</option>
								<option value="consolidated" <?= isset($procurement_type) && $procurement_type === 'consolidated' ? 'selected' : '' ?>>Consolidated</option>
							</select>
						</div>
					</div>
				</div>

				<!--------------------- ROW 3 --------------------->
				<div class="row pb-2">
					<div class="col-md-6">
						<div class="form-group">
							<label for="remarks_pr_no" class="control-label">Remarks (PR No.)</label>
							<input type="text"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   name="remarks_pr_no"
								   id="remarks_pr_no"
								   value="<?= htmlspecialchars($remarks_pr_no ?? '', ENT_QUOTES) ?>">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="philgeps_posting">WITH PHILGEPS POSTING</label>
							<select name="philgeps_posting" id="philgeps_posting" class="custom-select custom-select-sm">
								<option value="" <?= !isset($philgeps_posting) || $philgeps_posting === '' ? 'selected' : '' ?>>No Data Available</option>
								<option value="With Posting" <?= isset($philgeps_posting) && $philgeps_posting === 'With Posting' ? 'selected' : '' ?>>With Posting</option>
								<option value="Without Posting" <?= isset($philgeps_posting) && $philgeps_posting === 'Without Posting' ? 'selected' : '' ?>>Without Posting</option>
							</select>
						</div>
					</div>
				</div>

				<!--------------------- WITH POSTING --------------------->
				<div id="philgeps_section">
					<div class="row border-top pt-3">
						<div class="col-sm-12">
							<b class="border-bottom border-primary">WITH POSTING TO PHILGEPS </b>
						</div>
					</div>

					<div class="row pt-3">
						<div class="col-md-3">
							<div class="form-group">
								<label for="received_bac_third" class="control-label">received by BAC</label>
								<input type="date"
									   class="form-control form-control-sm"
									   autocomplete="off"
									   name="received_bac_third"
									   id="received_bac_third"
									   value="<?= isset($received_bac_third) && $received_bac_third !== '' ? htmlspecialchars(date('Y-m-d', strtotime($received_bac_third)), ENT_QUOTES) : '' ?>">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="rfq_no" class="control-label">RFQ NO.</label>
								<input type="text"
									   class="form-control form-control-sm"
									   autocomplete="off"
									   name="rfq_no"
									   id="rfq_no"
									   value="<?= htmlspecialchars($rfq_no ?? '', ENT_QUOTES) ?>">
							</div>
						</div>

						<div class="form-group col-md-3">
							<label for="reposting">Reposting</label>
							<select name="reposting" id="reposting" class="custom-select custom-select-sm">
								<option value="" <?= !isset($reposting) || $reposting === '' ? 'selected' : '' ?>>No Data Available</option>
								<option value="With Reposting" <?= isset($reposting) && $reposting === 'With Reposting' ? 'selected' : '' ?>>With Reposting</option>
								<option value="Without Reposting" <?= isset($reposting) && $reposting === 'Without Reposting' ? 'selected' : '' ?>>Without Reposting</option>
							</select>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="returned_gso_abstract" class="control-label">returned to GSO for abstract</label>
								<input type="date"
									   class="form-control form-control-sm"
									   autocomplete="off"
									   name="returned_gso_abstract"
									   id="returned_gso_abstract"
									   value="<?= isset($returned_gso_abstract) && $returned_gso_abstract !== '' ? htmlspecialchars(date('Y-m-d', strtotime($returned_gso_abstract)), ENT_QUOTES) : '' ?>">
							</div>
						</div>
					</div>
				</div>
				<!--------------------- ROW 4 --------------------->
				<div class="row border-top pt-3">
					<div class="col-md-6">
						<div class="form-group">
							<label for="supplier" class="control-label">Supplier</label>
							<input type="text"
								   id="supplier"
								   name="supplier"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   value="<?= htmlspecialchars($supplier ?? '', ENT_QUOTES) ?>">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="contract_cost" class="control-label">Contract cost</label>
							<input type="text"
								   id="contract_cost"
								   name="contract_cost"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   value="<?= htmlspecialchars($contract_cost ?? '', ENT_QUOTES) ?>">
						</div>
					</div>
				</div>

				<!--------------------- ROW 5 --------------------->
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="received_bac_second" class="control-label">received by BAC</label>
							<input type="date"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   name="received_bac_second"
								   id="received_bac_second"
								   value="<?= isset($received_bac_second) && $received_bac_second !== '' ? htmlspecialchars(date('Y-m-d', strtotime($received_bac_second)), ENT_QUOTES) : '' ?>">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="bac_reso_no" class="control-label">BAC Reso No</label>
							<input type="text"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   name="bac_reso_no"
								   id="bac_reso_no"
								   value="<?= htmlspecialchars($bac_reso_no ?? '', ENT_QUOTES) ?>">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="bac_reso_date" class="control-label">BAC Reso Date</label>
							<input type="date"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   name="bac_reso_date"
								   id="bac_reso_date"
								   value="<?= isset($bac_reso_date) && $bac_reso_date !== '' ? htmlspecialchars(date('Y-m-d', strtotime($bac_reso_date)), ENT_QUOTES) : '' ?>">
						</div>
					</div>
				</div>

				<!--------------------- ROW 6 --------------------->
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="received_gso_second" class="control-label">received by GSO</label>
							<input type="date"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   name="received_gso_second"
								   id="received_gso_second"
								   value="<?= isset($received_gso_second) && $received_gso_second !== '' ? htmlspecialchars(date('Y-m-d', strtotime($received_gso_second)), ENT_QUOTES) : '' ?>">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="po_no" class="control-label">PO no.</label>
							<input type="text"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   name="po_no"
								   id="po_no"
								   value="<?= htmlspecialchars($po_no ?? '', ENT_QUOTES) ?>">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="po_date" class="control-label">PO DATE</label>
							<input type="date"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   name="po_date"
								   id="po_date"
								   value="<?= isset($po_date) && $po_date !== '' ? htmlspecialchars(date('Y-m-d', strtotime($po_date)), ENT_QUOTES) : '' ?>">
						</div>
					</div>
				</div>

				<!--------------------- ROW 7 --------------------->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="air_no" class="control-label">AIR no.</label>
							<input type="text"
								   id="air_no"
								   name="air_no"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   value="<?= htmlspecialchars($air_no ?? '', ENT_QUOTES) ?>">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="air_date" class="control-label">AIR Date</label>
							<input type="date"
								   id="air_date"
								   name="air_date"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   value="<?= isset($air_date) && $air_date !== '' ? htmlspecialchars(date('Y-m-d', strtotime($air_date)), ENT_QUOTES) : '' ?>">
						</div>
					</div>
				</div>

				<!--------------------- ROW 8 --------------------->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="received_bo" class="control-label">Received by BO</label>
							<input type="date"
								   id="received_bo"
								   name="received_bo"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   value="<?= isset($received_bo) && $received_bo !== '' ? htmlspecialchars(date('Y-m-d', strtotime($received_bo)), ENT_QUOTES) : '' ?>">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="return_gso_completion" class="control-label">Return to GSO for completion of Docs</label>
							<input type="date"
								   id="return_gso_completion"
								   name="return_gso_completion"
								   class="form-control form-control-sm"
								   autocomplete="off"
								   value="<?= isset($return_gso_completion) && $return_gso_completion !== '' ? htmlspecialchars(date('Y-m-d', strtotime($return_gso_completion)), ENT_QUOTES) : '' ?>">
						</div>
					</div>
				</div>

				<!--------------------- ROW 9 --------------------->
				

				
			</form>
		</div>

		<!--------------------- SAVE --------------------->
		<div class="card-footer border-top border-info">
			<div class="d-flex w-100 justify-content-center align-items-center">
				<button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-project" >Save</button>
				<button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=document_list'">Cancel</button>
			</div>
		</div>
	</div>
</div>

<style>
/* Keep select controls visually consistent with text inputs in this form */
#manage-project .custom-select.custom-select-sm,
#manage-project select.custom-select,
#manage-project .select2-container--bootstrap4 .select2-selection--single {
	height: 34px !important;
	min-height: 34px !important;
	line-height: 1.2 !important;
	padding-top: 6px !important;
	padding-bottom: 6px !important;
	box-sizing: border-box !important;
}

/* Ensure Select2 single selection area positions arrow inside the box */
#manage-project .select2-container--bootstrap4 .select2-selection--single {
	position: relative !important;
	padding-right: 2.2rem !important; /* reserve space for arrow */
	overflow: hidden !important;
}

#manage-project .select2-container--bootstrap4 .select2-selection__rendered{
	line-height: 20px !important;
	padding-top: 0 !important;
	padding-bottom: 0 !important;
}

#manage-project .select2-container--bootstrap4 .select2-selection__arrow{
	position: absolute !important;
	right: 6px !important;
	top: 50% !important;
	transform: translateY(-50%) !important;
	height: auto !important;
}

/* Native select fallbacks: reserve right padding so OS arrow sits inside */
#manage-project .custom-select,
#manage-project select.form-control-sm,
#manage-project select {
	padding-right: 2.25rem !important;
	-webkit-appearance: menulist-button !important;
	-moz-appearance: menulist-button !important;
	appearance: menulist-button !important;
	background-repeat: no-repeat !important;
	background-position: right .75rem center !important;
}

/* In case some selects are rendered as form-control-sm */
#manage-project .form-control-sm, #manage-project select.form-control-sm {
	height: 34px !important;
	padding-top: 6px !important;
	padding-bottom: 6px !important;
	box-sizing: border-box !important;
}
</style>

<script>
	$('#manage-project').submit(function (e) {
		e.preventDefault()

		// Client-side validation for required fields
		var pr_no = $.trim($('[name="pr_no"]').val() || '');
		var particulars = $.trim($('[name="particulars"]').val() || '');
		var status = $('[name="status"]').val();
		var start_date = $.trim($('[name="start_date"]').val() || '');
		var end_date = $.trim($('[name="end_date"]').val() || '');

		var missing = [];
		if (!pr_no) missing.push('PR No.');
		if (!particulars) missing.push('Particulars');
		if (typeof status === 'undefined' || status === null || status === '') missing.push('Status');
		if (!start_date) missing.push('Start Date');
		if (!end_date) missing.push('Target End Date');

		if (missing.length > 0) {
			var msg = 'Please fill the following required fields: ' + missing.join(', ');
			alert_toast(msg, 'warning');
			return false;
		}

		start_load()
		$.ajax({
			url: 'ajax.php?action=save_project',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function (resp) {
				// Normalize and handle numeric id response or error message
				try{ resp = (typeof resp === 'string' ? resp.trim() : resp); }catch(e){}
				var id = parseInt(resp);
				if (!isNaN(id) && id > 0) {
					alert_toast('Data successfully saved', "success");
					setTimeout(function () {
						location.href = 'index.php?page=view_document&id=' + id;
					}, 800)
				} else {
					// Show server-provided message or a generic error
					var message = (typeof resp === 'string' && resp.trim() !== '') ? resp : 'An error occurred while saving.';
					alert_toast(message, 'danger');
					if (typeof end_load === 'function') end_load();
				}
			},
			error: function () {
				if (typeof end_load === 'function') end_load();
				alert_toast('An unexpected error occurred.', 'danger');
			}
		})


	})

	// Toggle PHILGEPS posting section visibility based on select value
	function togglePhilgepsSection() {
		var val = $('#philgeps_posting').val();
		if (val === 'With Posting') {
			$('#philgeps_section').show();
		} else {
			// Clear inputs inside the PHILGEPS section to avoid submitting stale data
			$('#philgeps_section').find('input, textarea').each(function(){
				$(this).val('');
			});
			// For selects, set to the empty/default option if present
			$('#philgeps_section').find('select').each(function(){
				if($(this).find('option[value=""]').length > 0){
					$(this).val('');
				} else {
					// fallback: select first option
					$(this).prop('selectedIndex', 0);
				}
			});
			$('#philgeps_section').hide();
		}
	}

	// Initialize visibility and bind change event
	$(function () {
		togglePhilgepsSection();
		$('#philgeps_posting').on('change', function () {
			togglePhilgepsSection();
		});
	});
</script>