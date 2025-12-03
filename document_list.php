<?php include'db_connect.php' ?>
<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <?php if($_SESSION['login_type'] != 3): ?>
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_document"><i class="fa fa-plus"></i> Add New Document</a>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <table class="table tabe-hover table-condensed" id="list">
                <colgroup>
                    <col width="10%">
                    <col width="35%">
                    <col width="15%">
                    <col width="15%">
                    <col width="15%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <tr>
                        <th>PR No.</th>
                        <th>Document</th>
                        <th>Date Started</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
                    // Show documents to all users (both Admin and Employee). Previously there were
                    // filters for manager or specific user ids; those are removed to allow global visibility.
                    include_once 'workflow_helper.php';
                    $where = "";
                    $qry = $conn->query("SELECT * FROM project_list $where order by id asc");
                    while($row= $qry->fetch_assoc()):
                        $trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
                        unset($trans['"'], $trans['<'], $trans['>'], $trans['<h2']);
                        $desc = strtr(html_entity_decode($row['supplier']),$trans);
                        $desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
                        // Initialize counters to avoid undefined variable warnings
                        // These default to 0; replace with real calculations if needed later.
                        $prod = 0;
                        $cprog = 0;

                        if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
                            if($prod  > 0  || $cprog > 0)
                                $row['status'] = 2;
                            else
                                $row['status'] = 1;
                        elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
                            $row['status'] = 4;
                        endif;
                    ?>
                    <tr>
                        <th>
                            <?php
                            // If procurement is consolidated, show all related PR Nos from `consolidated` table
                            if (isset($row['procurement_type']) && strcasecmp(trim($row['procurement_type']), 'consolidated') === 0) {
                                $cid = intval($row['id']);
                                $cres = $conn->query("SELECT pr_no FROM consolidated WHERE project_id = {$cid} ORDER BY COALESCE(row_order,id) ASC");
                                if ($cres && $cres->num_rows > 0) {
                                    $prs = array();
                                    while ($cr = $cres->fetch_assoc()) {
                                        $p = isset($cr['pr_no']) ? trim((string)$cr['pr_no']) : '';
                                        if ($p !== '') $prs[] = htmlspecialchars($p, ENT_QUOTES);
                                    }
                                    echo !empty($prs) ? implode('<br/>', $prs) : '&ndash;';
                                } else {
                                    echo (!empty(trim($row['pr_no']))) ? htmlspecialchars($row['pr_no'], ENT_QUOTES) : '&ndash;';
                                }
                            } else {
                                echo (!empty(trim($row['pr_no']))) ? htmlspecialchars($row['pr_no'], ENT_QUOTES) : '&ndash;';
                            }
                            ?>
                        </th>
                        <td>
                            <p><b><?php echo ucwords($row['particulars']) ?></b></p>
                            <?php $supplier_text = trim(strip_tags($desc)); ?>
                            <p class="truncate">
                                <?php echo $supplier_text; ?>
                                <?php if(isset($row['contract_cost']) && is_numeric($row['contract_cost']) && floatval($row['contract_cost']) > 0): ?>
                                    - Php <?php echo number_format($row['contract_cost'], 2); ?>
                                <?php endif; ?>
                            </p>
                        </td>
                        <td><b><?php echo date("M d, Y",strtotime($row['start_date'])) ?></b></td>
                        <td class="text-left">
                            <?php
                            // Progress calculation: percent complete = (# of completed input fields) / (total fields)
                            $fields_to_check = array(
                                'procurement_type','pr_no','amount','start_date','particulars','mop','received_bac_first',
                                'received_gso_first','philgeps_posting','rfq_no','returned_gso_abstract','supplier',
                                'contract_cost','received_bac_second','bac_reso_no','bac_reso_date','received_gso_second',
                                'po_no','po_date','air_no','air_date','received_treasury_first','received_bo_first','received_bo_second',
                                'return_gso_completion','received_accounting_first','received_treasury_second','received_mo',
                                'received_treasury_third','received_admin','received_accounting_second','received_treasury_fourth',
                                'cheque_no'
                            );
                            $total_fields = count($fields_to_check);
                            $filled = 0;
                            foreach($fields_to_check as $f){
                                $val = '';
                                if(isset($row[$f])) $val = trim((string)$row[$f]);
                                // treat values containing SQL zero-dates as empty
                                if($val !== '' && strpos($val,'0000-00-00') === false){
                                    $filled++;
                                    continue;
                                }
                                // Special-case: if field is pr_no and this project is a consolidated
                                // procurement, consider it 'filled' when consolidated child rows exist
                                if ($f === 'pr_no' && isset($row['procurement_type']) && strcasecmp(trim($row['procurement_type']), 'consolidated') === 0) {
                                    $cid = intval($row['id']);
                                    $cres = $conn->query("SELECT 1 FROM consolidated WHERE project_id = {$cid} LIMIT 1");
                                    if ($cres && $cres->num_rows > 0) {
                                        $filled++;
                                        continue;
                                    }
                                }
                            }
                            $percent_raw = ($total_fields > 0) ? ($filled / $total_fields) * 100.0 : 0.0;
                            $percent_raw = max(0.0, min(100.0, $percent_raw));
                            $percent_label = number_format($percent_raw, 2) . '%';
                            ?>
                            <div class="progress" style="height:8px;background-color:#e9ecef;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $percent_raw; ?>%;" aria-valuenow="<?php echo $percent_raw; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="small text-muted mt-1"><?php echo $percent_label; ?> Complete</div>
                        </td>
                        <td class="text-left">
                            <?php
                                // compute workflow stage from filled fields
                                $ws = compute_workflow_stage($conn, $row);
                                echo "<span class='badge {$ws['badge']}'>" . htmlspecialchars($ws['stage'], ENT_QUOTES) . "</span>";
                                // Payment status: if cheque_no exists show Paid/Unpaid based on the `paid` checkbox
                                $cheque = isset($row['cheque_no']) ? trim((string)$row['cheque_no']) : '';
                                if ($cheque !== '') {
                                    $is_paid = false;
                                    if (isset($row['paid']) && in_array(strtolower((string)$row['paid']), array('1', 'yes', 'true'))) {
                                        $is_paid = true;
                                    }
                                    $pclass = $is_paid ? 'badge-success' : 'badge-danger';
                                    $plabel = $is_paid ? 'Paid' : 'Unpaid';
                                    echo " <br/><span class='badge {$pclass}'>" . $plabel . "</span>";
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            Action
                            </button>
                            <div class="dropdown-menu">
                            <a class="dropdown-item view_project" href="./index.php?page=view_document&id=<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>">View</a>
                            <div class="dropdown-divider"></div>
                            <?php if($_SESSION['login_type'] != 3): ?>
                            <a class="dropdown-item" href="./index.php?page=edit_document&id=<?php echo $row['id'] ?>">Edit</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
                        <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    table p{
        margin: unset !important;
    }
    table td{
        vertical-align: middle !important
    }
</style>
<script>
    $(document).ready(function(){
        $('#list').dataTable()

        $('.delete_project').click(function(){
            _conf("Are you sure to delete this project?","delete_project",[$(this).attr('data-id')])
        })
    })
    function delete_project($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_project',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Data successfully deleted",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)

                }
            }
        })
    }
</script>
