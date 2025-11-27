<?php
include 'db_connect.php';
$stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
$qry = $conn->query("SELECT * FROM project_list where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
// Ensure progress counters are defined to avoid warnings
$prod = isset($prod) ? $prod : 0;
$cprog = isset($cprog) ? $cprog : 0;

$manager = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id = $manager_id");
$manager = $manager->num_rows > 0 ? $manager->fetch_array() : array();
?>
<div class="col-lg-12">
	<div class="row">
		<div class="col-md-12">
			<div class="d-flex w-100 align-items-center mb-2">
				<div>
					<button type="button" id="back_document" class="btn btn-default btn-sm btn-flat" title="Back">
						<i class="fa fa-arrow-left"></i>
						<span class="ml-1">Back</span>
					</button>
				</div>
				<div class="ml-auto">
					<a id="edit_document" href="./index.php?page=edit_document&id=<?php echo $_GET['id'] ?>" class="btn btn-default btn-sm btn-flat" title="Edit">
						<i class="fa fa-edit"></i>
						<span class="ml-1">Edit</span>
					</a>
				</div>
			</div>

			<div class="callout callout-info">
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-10">
							<dl>
								<dt><b class="border-bottom border-primary">Particulars</b></dt>
								<dd><?php echo (!empty(trim($particulars))) ? ucwords($particulars) : 'No Data Available'; ?></dd>
								<dt><b class="border-bottom border-primary">Purchase Request No.</b></dt>
								<dd><?php echo (!empty(trim($pr_no))) ? html_entity_decode($pr_no) : 'No Data Available'; ?></dd>
							</dl>
						</div>

						<div class="col-md-2">
							<dl>
								<dt><b class="border-bottom border-primary">Status</b></dt>
								<dd>
									<?php
									if ($stat[$status] == 'Pending') {
										echo "<span class='badge badge-secondary'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'Started') {
										echo "<span class='badge badge-primary'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'On-Progress') {
										echo "<span class='badge badge-info'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'On-Hold') {
										echo "<span class='badge badge-warning'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'Over Due') {
										echo "<span class='badge badge-danger'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'Done') {
										echo "<span class='badge badge-success'>{$stat[$status]}</span>";
									}
									?>
								</dd>
							</dl>

							<dl>
								<dt><b class="border-bottom border-primary">Start Date</b></dt>
								<dd><?php echo (!empty($start_date) && strpos($start_date,'0000-00-00') === false) ? date("F d, Y", strtotime($start_date)) : 'No Specified Date'; ?></dd>
							</dl>
						</div>
					</div>

					<div class="row border-top pt-3">
						<div class="col-sm-3">
							<dl>
								<dt><b class="border-bottom border-primary">Amount</b></dt>
								<dd><?php echo (!empty($amount) && is_numeric($amount)) ? 'Php '.number_format($amount, 2) : 'No Data Available'; ?></dd>
							</dl>

							<dl>
								<dt><b class="border-bottom border-primary">received by BAC from BO</b></dt>
								<dd><?php echo (!empty($received_bac_first) && strpos($received_bac_first,'0000-00-00') === false) ? date("F d, Y, h:i A", strtotime($received_bac_first)) : 'No Specified Date'; ?></dd>
							</dl>

							<dl>
								<dt><b class="border-bottom border-primary">PHILGEPS Posting</b></dt>
								<dd><?php echo (!empty(trim($philgeps_posting))) ? ucwords($philgeps_posting) : 'No Data Available'; ?></dd>
							</dl>
						</div>

						<div class="col-md-3">
							<dl>
								<dt><b class="border-bottom border-primary">Mode of Payment</b></dt>
								<dd><?php echo (!empty(trim($mop))) ? strtoupper($mop) : 'No Data Available'; ?></dd>
							</dl>

							<dl>
								<dt><b class="border-bottom border-primary">Received by GSO</b></dt>
								<dd><?php echo (!empty($received_gso_first) && strpos($received_gso_first,'0000-00-00') === false) ? date("F d, Y, h:i A", strtotime($received_gso_first)) : 'No Specified Date'; ?></dd>
							</dl>
						</div>

						<div class="col-md-3">
							<dl>
								<dt><b class="border-bottom border-primary">Procurement Type</b></dt>
								<dd><?php echo (!empty(trim($procurement_type))) ? ucwords($procurement_type) : 'No Data Available'; ?></dd>
							</dl>

							<dl>
								<dt><b class="border-bottom border-primary">Supplier</b></dt>
								<dd><?php echo (!empty(trim($supplier ?? ''))) ? htmlspecialchars($supplier, ENT_QUOTES) : 'No Data Available'; ?></dd>
							</dl>
						</div>

						<div class="col-md-3">
							<dl>
								<dt><b class="border-bottom border-primary">Remarks Purchase Request No.</b></dt>
								<dd><?php echo (!empty(trim($remarks_pr_no))) ? htmlspecialchars($remarks_pr_no, ENT_QUOTES) : 'No Data Available'; ?></dd>
							</dl>

							<dl>
								<dt><b class="border-bottom border-primary">Contract Cost</b></dt>
								<dd><?php echo (!empty($contract_cost) && is_numeric($contract_cost)) ? 'Php '.number_format($contract_cost, 2) : 'No Data Available'; ?></dd>
							</dl>
						</div>
					</div>

					<?php if (isset($philgeps_posting) && $philgeps_posting === 'With Posting'): ?>
					<div class="row border-top pt-3">
						<div class="col-sm-12">
							<b>WITH POSTING TO PHILGEPS</b>
						</div>
					</div>

					<div class="row pt-3">
						<div class="col-sm-3">
							<dl>
								<dt><b class="border-bottom border-primary">Received by BAC</b></dt>
								<dd><?php echo (!empty($received_bac_third) && strpos($received_bac_third,'0000-00-00') === false) ? date("F d, Y, h:i A", strtotime($received_bac_third)) : 'No Specified Date'; ?></dd>
							</dl>
						</div>

						<div class="col-md-3">
							<dl>
								<dt><b class="border-bottom border-primary">RFQ No.</b></dt>
								<dd><?php echo (!empty(trim($rfq_no))) ? html_entity_decode($rfq_no) : 'No Data Available'; ?></dd>
							</dl>
							
						</div>

						<div class="col-md-3"> 	
							<dl>
								<dt><b class="border-bottom border-primary">Reposting</b></dt>
								<dd><?php echo (!empty(trim($reposting))) ? ucwords($reposting) : 'No Data Available'; ?></dd>
							</dl>
						</div>

						<div class="col-md-3">
							<dl>
								<dt><b class="border-bottom border-primary">Returned to GSO for Abstract</b></dt>
								<dd><?php echo (!empty($returned_gso_abstract) && strpos($returned_gso_abstract,'0000-00-00') === false) ? date("F d, Y, h:i A", strtotime($returned_gso_abstract)) : 'No Specified Date'; ?></dd>
							</dl>
						</div>
					</div>
					<?php endif; ?>

					<div class="row border-top pt-3">
						<div class="col-sm-12">
							<b>For BAC Resolution</b>
						</div>
					</div>

					<div class="row pt-3">
						<div class="col-sm-3">
							<dl>
								<dt><b class="border-bottom border-primary">Received by BAC</b></dt>
								<dd><?php echo (!empty($received_bac_second) && strpos($received_bac_second,'0000-00-00') === false) ? date("F d, Y, h:i A", strtotime($received_bac_second)) : 'No Specified Date'; ?></dd>
							</dl>
						</div>

						<div class="col-md-3">
							<dl>
								<dt><b class="border-bottom border-primary">BAC Resolution No</b></dt>
								<dd><?php echo (!empty(trim($bac_reso_no))) ? htmlspecialchars($bac_reso_no, ENT_QUOTES) : 'No Data Available'; ?></dd>
							</dl>
						</div>

						<div class="col-sm-3">
							<dl>
								<dt><b class="border-bottom border-primary">BAC Resolution Date</b></dt>
								<dd><?php echo (!empty($bac_reso_date) && strpos($bac_reso_date,'0000-00-00') === false) ? date("F d, Y, h:i A", strtotime($bac_reso_date)) : 'No Specified Date'; ?></dd>
							</dl>
						</div>
					</div>

					<div class="row border-top pt-3">
						<div class="col-sm-12">
							<b>For Purchase Order</b>
						</div>
					</div>

					<div class="row pt-3">
						<div class="col-sm-3">
							<dl>
								<dt><b class="border-bottom border-primary">Received by GSO</b></dt>
								<dd><?php echo (!empty($received_gso_second) && strpos($received_gso_second,'0000-00-00') === false) ? date("F d, Y, h:i A", strtotime($received_gso_second)) : 'No Specified Date'; ?></dd>
							</dl>
						</div>

						<div class="col-md-3">
							<dl>
								<dt><b class="border-bottom border-primary">Purchase Order No</b></dt>
								<dd><?php echo (!empty(trim($po_no))) ? htmlspecialchars($po_no, ENT_QUOTES) : 'No Data Available'; ?></dd>
							</dl>
						</div>

						<div class="col-sm-3">
							<dl>
								<dt><b class="border-bottom border-primary">Purchase Order Date</b></dt>
								<dd><?php echo (!empty($po_date) && strpos($po_date,'0000-00-00') === false) ? date("F d, Y, h:i A", strtotime($po_date)) : 'No Specified Date'; ?></dd>
							</dl>
						</div>
					</div>

					<div class="row border-top pt-3">
						<div class="col-md-3">
							<dl>
								<dt><b class="border-bottom border-primary">AIR No</b></dt>
								<dd><?php echo (!empty(trim($air_no))) ? htmlspecialchars($air_no, ENT_QUOTES) : 'No Data Available'; ?></dd>
							</dl>
						</div>

						<div class="col-sm-3">
							<dl>
								<dt><b class="border-bottom border-primary">Air Date</b></dt>
								<dd><?php echo (!empty($air_date) && strpos($air_date,'0000-00-00') === false) ? date("F d, Y, h:i A", strtotime($air_date)) : 'No Specified Date'; ?></dd>
							</dl>
						</div>
					</div>

					<div class="row border-top pt-3">
						<div class="col-sm-12">
							<b>Certification on Appropriations, Funds, and Obligation of Allotment</b>
						</div>
					</div>

					<div class="row pt-3">
						<div class="col-sm-3">
							<dl>
								<dt><b class="border-bottom border-primary">Received by BO</b></dt>
								<dd><?php echo (!empty($received_bo) && strpos($received_bo,'0000-00-00') === false) ? date("F d, Y, h:i A", strtotime($received_bo)) : 'No Specified Date'; ?></dd>
							</dl>
						</div>

						<div class="col-sm-4">
							<dl>
								<dt><b class="border-bottom border-primary">Return to GSO for completion of Docs</b></dt>
								<dd><?php echo (!empty($return_gso_completion) && strpos($return_gso_completion,'0000-00-00') === false) ? date("F d, Y, h:i A", strtotime($return_gso_completion)) : 'No Specified Date'; ?></dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Comments:</b></span>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_comment"><i class="fa fa-plus"></i> New Comment</button>
					</div>
				</div>

				<div class="card-body p-0">
					<div class="table-responsive">
					<table id="comments-list" class="table table-condensed m-0 table-hover">
							<colgroup>
								<col width="5%">
								<col width="15%">
								<col width="45%">
								<col width="15%">
								<col width="10%">
							</colgroup>
							<thead>
								<th>#</th>
								<th>User</th>
								<th>Comment</th>
								<th>Date/Time</th>
								<th class="text-center">Action</th>
							</thead>
							<tbody>

						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
	.users-list>li img {
		border-radius: 50%;
		height: 67px;
		width: 67px;
		object-fit: cover;
	}

	.users-list>li {
		width: 33.33% !important
	}

	.truncate {
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 1 !important;
		line-clamp: 1 !important;
		overflow: hidden;
		text-overflow: ellipsis;
	}
</style>

<script>
	function escapeHtml(text) {
		return text
			? text.replace(/&/g, '&amp;')
				  .replace(/</g, '&lt;')
				  .replace(/>/g, '&gt;')
				  .replace(/"/g, '&quot;')
				  .replace(/'/g, '&#039;')
			: '';
	}

	function load_comments() {
		if (typeof start_load === 'function') start_load();
		$.ajax({
			url: 'load_comments.php',
			method: 'POST',
			data: { project_id: '<?php echo $_GET['id'] ?>' },
			success: function (resp) {
				try {
					var data = typeof resp === 'object' ? resp : JSON.parse(resp);
				} catch (e) {
					data = [];
				}
				var tr = '';
				if (Array.isArray(data) && data.length > 0) {
							data.forEach(function (el, i) {
								tr += '<tr>' +
									'<td>' + (i + 1) + '</td>' +
									'<td>' + escapeHtml(el.user || 'Unknown') + '</td>' +
									'<td>' + escapeHtml(el.comment || '') + '</td>' +
									'<td>' + escapeHtml(el.date_created || '') + '</td>' +
									'<td class="text-center">'
									+ '<button type="button" class="btn btn-sm btn-default btn-flat comment-view" data-id="' + el.id + '" data-user="' + escapeHtml(el.user || '') + '" data-comment="' + encodeURIComponent(el.comment || '') + '" title="View"><i class="fa fa-eye"></i></button> '
									+ '<button type="button" class="btn btn-sm btn-danger btn-flat comment-delete" data-id="' + el.id + '" title="Delete"><i class="fa fa-trash"></i></button>'
									+ '</td>'
									+ '</tr>';
							});
						} else {
							tr = '<tr><td colspan="5" class="text-center">No comments found.</td></tr>';
						}
				$('#comments-list tbody').html(tr);
				if (typeof end_load === 'function') end_load();
			},
			error: function () {
				$('#comments-list tbody').html('<tr><td colspan="4" class="text-center">An error occurred while loading comments.</td></tr>');
				if (typeof end_load === 'function') end_load();
			}
		})
	}

	$(document).ready(function () {
		load_comments();
	})

	$('#new_comment').click(function () {
		uni_modal("New Comment", "manage_comment.php?project_id=<?php echo $_GET['id'] ?>")
	})

	// Back button handler
	$('#back_document').click(function(){
		// Always go to the document list page
		window.location.href = './index.php?page=document_list';
	})

	// Delegate view and delete handlers for dynamic comment rows
	$(document).on('click', '.comment-view', function () {
		var user = $(this).attr('data-user') || 'Comment';
		var comment = decodeURIComponent($(this).attr('data-comment') || '');
		var html = '<div style="text-align:left"><strong>' + escapeHtml(user) + '</strong><hr/><pre style="white-space:pre-wrap;">' + escapeHtml(comment) + '</pre></div>';
		Swal.fire({
			title: 'View Comment',
			html: html,
			width: 600
		});
	});

	$(document).on('click', '.comment-delete', function () {
		var id = $(this).attr('data-id');
		_conf("Are you sure to delete this comment?", "delete_comment", [id])
	});

	function delete_comment(id){
		start_load();
		$.ajax({
			url: 'ajax.php?action=delete_comment',
			method: 'POST',
			data: {id: id},
			success: function (resp) {
				// Normalize response to avoid whitespace/HTML around returned values
				try{ resp = (typeof resp === 'string' ? resp.trim() : resp); }catch(e){}
				if (resp == 1) {
					alert_toast('Comment successfully deleted', 'success');
					$('#confirm_modal').modal('hide');
					load_comments();
				} else {
					alert_toast('Failed to delete comment', 'danger');
				}
				// ensure confirm modal is hidden and loader ended
				$('#confirm_modal').modal('hide');
				if (typeof end_load === 'function') end_load();
			},
			error: function () {
				// hide confirm and end loader on error as well
				$('#confirm_modal').modal('hide');
				if (typeof end_load === 'function') end_load();
				alert_toast('An error occurred.', 'danger');
			}
		})
	}
</script>