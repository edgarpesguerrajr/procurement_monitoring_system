<?php
// Helper to compute workflow stage based on answered fields
function compute_workflow_stage($conn, $row){
    // normalize row values
    $get = function($k) use ($row){
        if(!isset($row[$k])) return '';
        $v = trim((string)$row[$k]);
        if($v === '0000-00-00' || $v === '0000-00-00 00:00:00') return '';
        return $v;
    };

    // helper to check pr_no presence (consider consolidated rows)
    $has_pr = false;
    if($get('pr_no') !== '') {
        $has_pr = true;
    } else {
        // check consolidated children
        if(isset($row['id']) && is_numeric($row['id'])){
            $pid = intval($row['id']);
            $cres = $conn->query("SELECT pr_no FROM consolidated WHERE project_id = {$pid} AND COALESCE(pr_no,'') <> '' LIMIT 1");
            if($cres && $cres->num_rows > 0) $has_pr = true;
        }
    }

    // stages in order of progression
    $stages = [
        'Started' => ['procurement_type','pr_no','amount','start_date','particulars','mop'],
        'On Going' => ['received_bac_first','received_gso_first','philgeps_posting','rfq_no','returned_gso_abstract'],
        'Processing' => ['supplier','contract_cost'],
        'BAC Reso' => ['received_bac_second','bac_reso_no','bac_reso_date'],
        'PO' => ['received_gso_second','po_no','po_date'],
        'AIR' => ['air_no','air_date','received_treasury_first','received_bo_first'],
        'CAFOA' => ['received_bo_second','return_gso_completion'],
        'DISBURSMENT' => ['received_accounting_first','received_treasury_second','received_mo','received_treasury_third','received_admin','received_accounting_second','received_treasury_fourth','cheque_no']
    ];

    $current = 'Started';
    foreach($stages as $name => $fields){
        $found = false;
        foreach($fields as $f){
            if($f === 'pr_no'){
                if($has_pr){ $found = true; break; }
                continue;
            }
            if($get($f) !== ''){ $found = true; break; }
        }
        if($found) $current = $name; else break; // stop at first stage that has no fields
    }

    // If we reached Disbursement but the record is marked paid, consider it Done
    if ($current === 'DISBURSMENT') {
        $paid_val = '';
        if (isset($row['paid'])) $paid_val = trim((string)$row['paid']);
        if ($paid_val !== '' && in_array(strtolower($paid_val), array('1','yes','true'))) {
            $current = 'Done';
        }
    }

    // map to badge classes (tune as needed)
    // Use badge classes defined in project to_do list
    $badge_map = [
        'Started' => 'badge-secondary',
        'On Going' => 'badge-primary',
        'Processing' => 'badge-primary',
        'BAC Reso' => 'badge-primary',
        'PO' => 'badge-primary',
        'AIR' => 'badge-primary',
        'CAFOA' => 'badge-primary',
        'DISBURSMENT' => 'badge-warning',
        'Done' => 'badge-success'
    ];

    $badge = isset($badge_map[$current]) ? $badge_map[$current] : 'badge-secondary';
    return ['stage'=>$current, 'badge'=>$badge];
}
