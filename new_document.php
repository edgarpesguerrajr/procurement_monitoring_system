<?php if (!isset($conn)) {
    include 'db_connect.php';
}

// Helper: safely format datetime values from DB. Returns empty string for empty/zero/invalid dates.
if(!function_exists('safeFormatDatetime')){
    function safeFormatDatetime($val, $format = 'Y/m/d H:i'){
        if(!isset($val) || $val === null) return '';
        $v = trim($val);
        if($v === '') return '';
        // treat MySQL zero-date or placeholder-like values as empty
        if(strpos($v,'0000-00-00') !== false) return '';
        // strtotime returns false on invalid dates
        $ts = strtotime($v);
        if($ts === false || $ts <= 0) return '';
        return htmlspecialchars(date($format, $ts), ENT_QUOTES);
    }
}
?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="" id="manage-project">

                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

                <!--------------------- Top --------------------->
                <div class="row">
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
                <!--------------------- SINGLE --------------------->
                <div id="single_section" <?= (!isset($procurement_type) || $procurement_type === '' || $procurement_type === 'single') ? '' : 'style="display:none;"' ?>>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">PR No.</label>
                                <input type="text" class="form-control form-control-sm" autocomplete="off" name="pr_no" value="<?= htmlspecialchars($pr_no ?? '', ENT_QUOTES) ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Amount</label>
                                <input type="text" class="form-control form-control-sm amount-input" autocomplete="off" name="amount" value="<?= htmlspecialchars($amount ?? '', ENT_QUOTES) ?>">
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
                                    value="<?= safeFormatDatetime($start_date ?? '','Y-m-d') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Particulars</label>
                                <input type="text" class="form-control form-control-sm" autocomplete="off" name="particulars" value="<?= htmlspecialchars($particulars ?? '', ENT_QUOTES) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!--------------------- CONSOLIDATED --------------------->
                <div id="consolidated_preview" <?= (isset($procurement_type) && $procurement_type === 'consolidated') ? '' : 'style="display:none;"' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Particulars</label>
                                <input type="text" class="form-control form-control-sm" autocomplete="off" name="particulars" value="<?= htmlspecialchars($particulars ?? '', ENT_QUOTES) ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Grand Total</label>
                                <input type="text" id="consolidated_grand_total" class="form-control form-control-sm amount-input" autocomplete="off" name="amount" value="<?= htmlspecialchars($amount ?? '', ENT_QUOTES) ?>" readonly>
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
                                    value="<?= safeFormatDatetime($start_date ?? '','Y-m-d') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Show this block only for 'consolidated' procurement_type -->
                <div id="consolidated_section" <?= (isset($procurement_type) && $procurement_type === 'consolidated') ? '' : 'style="display:none;"' ?> >
                    <div class="row border-top pt-3">
                        <div class="col-sm-12">
                            <b class="border-bottom border-primary">For Consolidated</b>
                        </div>
                    </div>

                    <div class="row pt-3 pb-3">
                        <div class="col-12">
                            <!-- Header row: show labels once -->
                            <div class="consolidated-header row mb-2 font-weight-bold align-items-center">
                                <div class="col-md-3">PR No.</div>
                                <div class="col-md-3">Amount</div>
                                <div class="col-md-5">Particulars</div>
                                <div class="col-md-1"></div>
                            </div>
                            <div id="consolidated_rows">
                                <?php
                                // Prepare arrays for existing values (backward compatible with scalar values)
                                $pr_nos = [];
                                $amounts = [];
                                $parts = [];
                                // Attempt to load consolidated rows from database when editing an existing project
                                $grand_total = 0;
                                if(isset($id) && !empty($id) && isset($conn)){
                                    $pid = intval($id);
                                    $res = $conn->query("SELECT pr_no, amount, particulars FROM consolidated WHERE project_id = {$pid} ORDER BY id ASC");
                                    if($res && $res->num_rows > 0){
                                        while($row = $res->fetch_assoc()){
                                            $pr_nos[] = $row['pr_no'];
                                            $amounts[] = $row['amount'];
                                            $parts[] = $row['particulars'];
                                            $grand_total += (float)$row['amount'];
                                        }
                                        // set scalar amount (grand total) for preview; keep plain numeric (no commas)
                                        $amount = $grand_total !== 0 ? number_format($grand_total,2,'.','') : ($amount ?? '');
                                    } else {
                                        // fallback to legacy behavior when no consolidated rows found or query failed
                                        if(isset($pr_no)){
                                            if(is_array($pr_no)) $pr_nos = $pr_no;
                                            elseif($pr_no !== '') $pr_nos = [$pr_no];
                                        }
                                        if(isset($amount)){
                                            if(is_array($amount)) $amounts = $amount;
                                            elseif($amount !== '') $amounts = [$amount];
                                        }
                                        if(isset($particulars)){
                                            if(is_array($particulars)) $parts = $particulars;
                                            elseif($particulars !== '') $parts = [$particulars];
                                        }
                                    }
                                } else {
                                    // create arrays from scalar or array inputs when not editing an existing project
                                    if(isset($pr_no)){
                                        if(is_array($pr_no)) $pr_nos = $pr_no;
                                        elseif($pr_no !== '') $pr_nos = [$pr_no];
                                    }
                                    if(isset($amount)){
                                        if(is_array($amount)) $amounts = $amount;
                                        elseif($amount !== '') $amounts = [$amount];
                                    }
                                    if(isset($particulars)){
                                        if(is_array($particulars)) $parts = $particulars;
                                        elseif($particulars !== '') $parts = [$particulars];
                                    }
                                }
                                $max = max([count($pr_nos), count($amounts), count($parts), 1]);
                                for($i = 0; $i < $max; $i++):
                                ?>
                                <div class="consolidated-row row mb-2 align-items-start">
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <input type="text" class="form-control form-control-sm" autocomplete="off" name="pr_no[]" placeholder="PR No." value="<?= htmlspecialchars($pr_nos[$i] ?? '', ENT_QUOTES) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <input type="text" class="form-control form-control-sm amount-input" autocomplete="off" name="amount[]" placeholder="Amount" value="<?= htmlspecialchars($amounts[$i] ?? '', ENT_QUOTES) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-0">
                                            <input type="text" class="form-control form-control-sm" autocomplete="off" name="particulars[]" placeholder="Particulars" value="<?= htmlspecialchars($parts[$i] ?? '', ENT_QUOTES) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center justify-content-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-success add-consolidated" title="Add row">+</button>
                                            <?php if($i > 0): ?>
                                                <button type="button" class="btn btn-danger remove-consolidated" title="Remove row">-</button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-danger remove-consolidated" style="display:none;" title="Remove row">-</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--------------------- ROW 3 --------------------->
                <div class="row border-top pt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mop">Mode of Procurement</label>
                            <select name="mop" id="mop" class="custom-select custom-select-sm">
                                <option value="" <?= !isset($mop) || $mop === '' ? 'selected' : '' ?>>No Data Available</option>
                                <option value="Single Value Procurement" <?= isset($mop) && $mop === 'Single Value Procurement' ? 'selected' : '' ?>>Single Value Procurement</option>
                                <option value="lease" <?= isset($mop) && $mop === 'lease' ? 'selected' : '' ?>>LEASE OF VENUE</option>
                                <option value="repeat" <?= isset($mop) && $mop === 'repeat' ? 'selected' : '' ?>>REPEAT ORDER</option>
                                <option value="a_to_a" <?= isset($mop) && $mop === 'a_to_a' ? 'selected' : '' ?>>A-TO-A</option>
                                <option value="direct" <?= isset($mop) && $mop === 'direct' ? 'selected' : '' ?>>DIRECT</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="received_bac_first">Received by BAC from BO</label>
                                <input type="text"
                                    class="form-control form-control-sm datetimepicker"
                                    autocomplete="off"
                                    name="received_bac_first"
                                    id="received_bac_first"
                                    value="<?= safeFormatDatetime($received_bac_first ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_bac_first_now" data-target="received_bac_first" <?php if(isset($received_bac_first) && $received_bac_first !== '' && strpos($received_bac_first,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_bac_first_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="received_gso_first">Received by GSO for RFQ</label>
                                 <input type="text"
                                     class="form-control form-control-sm datetimepicker"
                                     autocomplete="off"
                                     name="received_gso_first"
                                     id="received_gso_first"
                                     value="<?= safeFormatDatetime($received_gso_first ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_gso_first_now" data-target="received_gso_first" <?php if(isset($received_gso_first) && $received_gso_first !== '' && strpos($received_gso_first,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_gso_first_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="philgeps_posting">With PHILGEPS Posting</label>
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
                            <b class="border-bottom border-primary">With Posting to PHILGEPS </b>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="received_bac_third" class="control-label">Received by BAC</label>
                                    <input type="text"
                                        class="form-control form-control-sm datetimepicker"
                                        autocomplete="off"
                                        name="received_bac_third"
                                        id="received_bac_third"
                                        value="<?= safeFormatDatetime($received_bac_third ?? '') ?>">
                                    <div class="ml-2">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input auto-timestamp" type="checkbox" id="received_bac_third_now" data-target="received_bac_third" <?php if(isset($received_bac_third) && $received_bac_third !== '' && strpos($received_bac_third,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                            <label class="form-check-label small" for="received_bac_third_now">Received</label>
                                        </div>
                                    </div>
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
                                <label for="returned_gso_abstract" class="control-label">Returned to GSO for Abstract</label>
                                    <input type="text"
                                        class="form-control form-control-sm datetimepicker"
                                        autocomplete="off"
                                        name="returned_gso_abstract"
                                        id="returned_gso_abstract"
                                        value="<?= safeFormatDatetime($returned_gso_abstract ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="returned_gso_abstract_now" data-target="returned_gso_abstract" <?php if(isset($returned_gso_abstract) && $returned_gso_abstract !== '' && strpos($returned_gso_abstract,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="returned_gso_abstract_now">Received</label>
                                    </div>
                                </div>
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
                                   class="form-control form-control-sm amount-input"
                                   autocomplete="off"
                                   value="<?= htmlspecialchars($contract_cost ?? '', ENT_QUOTES) ?>">
                        </div>
                    </div>
                </div>

                <!--------------------- ROW 5 --------------------->
                <div class="row border-top pt-3">
                    <div class="col-sm-12">
                        <b class="border-bottom border-primary">For BAC Resolution </b>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-md-4">
                            <div class="form-group">
                                <label for="received_bac_second" class="control-label">Received by BAC</label>
                                          <input type="text"
                                              class="form-control form-control-sm datetimepicker"
                                              autocomplete="off"
                                              name="received_bac_second"
                                              id="received_bac_second"
                                              value="<?= safeFormatDatetime($received_bac_second ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_bac_second_now" data-target="received_bac_second" <?php if(isset($received_bac_second) && $received_bac_second !== '' && strpos($received_bac_second,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_bac_second_now">Received</label>
                                    </div>
                                </div>
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
                                          <input type="text"
                                              class="form-control form-control-sm datetimepicker"
                                              autocomplete="off"
                                              name="bac_reso_date"
                                              id="bac_reso_date"
                                              value="<?= safeFormatDatetime($bac_reso_date ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="bac_reso_date_now" data-target="bac_reso_date" <?php if(isset($bac_reso_date) && $bac_reso_date !== '' && strpos($bac_reso_date,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="bac_reso_date_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <!--------------------- ROW 6 --------------------->
                <div class="row border-top pt-3">
                    <div class="col-sm-12">
                        <b class="border-bottom border-primary">For Purchase Order </b>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-md-4">
                            <div class="form-group">
                                <label for="received_gso_second" class="control-label">Received by GSO</label>
                                          <input type="text"
                                              class="form-control form-control-sm datetimepicker"
                                              autocomplete="off"
                                              name="received_gso_second"
                                              id="received_gso_second"
                                              value="<?= safeFormatDatetime($received_gso_second ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_gso_second_now" data-target="received_gso_second" <?php if(isset($received_gso_second) && $received_gso_second !== '' && strpos($received_gso_second,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_gso_second_now">Received</label>
                                    </div>
                                </div>
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
                                <input type="text"
                                    class="form-control form-control-sm datetimepicker"
                                    autocomplete="off"
                                    name="po_date"
                                    id="po_date"
                                    value="<?= safeFormatDatetime($po_date ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="po_date_now" data-target="po_date" <?php if(isset($po_date) && $po_date !== '' && strpos($po_date,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="po_date_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <!--------------------- ROW 7 --------------------->
                <div class="row border-top pt-3">
                    <div class="col-sm-12">
                        <b class="border-bottom border-primary">For AIR </b>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-md-3">
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

                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="air_date" class="control-label">AIR Date (GSO)</label>
                                <input type="text"
                                id="air_date"
                                name="air_date"
                                class="form-control form-control-sm datetimepicker"
                                autocomplete="off"
                                value="<?= safeFormatDatetime($air_date ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="air_date_now" data-target="air_date" <?php if(isset($air_date) && $air_date !== '' && strpos($air_date,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="air_date_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="received_treasury_first" class="control-label">Received by Treasury</label>
                                <input type="text"
                                id="received_treasury_first"
                                name="received_treasury_first"
                                class="form-control form-control-sm datetimepicker"
                                autocomplete="off"
                                value="<?= safeFormatDatetime($received_treasury_first ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_treasury_first_now" data-target="received_treasury_first" <?php if(isset($received_treasury_first) && $received_treasury_first !== '' && strpos($received_treasury_first,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_treasury_first_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="received_bo_first" class="control-label">Received by BO</label>
                                <input type="text"
                                    id="received_bo_first"
                                    name="received_bo_first"
                                    class="form-control form-control-sm datetimepicker"
                                    autocomplete="off"
                                    value="<?= safeFormatDatetime($received_bo_first ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_bo_first_now" data-target="received_bo_first" <?php if(isset($received_bo_first) && $received_bo_first !== '' && strpos($received_bo_first,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_bo_first_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <!--------------------- ROW 8 --------------------->
                <div class="row border-top pt-3">
                    <div class="col-sm-12">
                        <b class="border-bottom border-primary">Certification on Appropriations, Funds, and Obligation of Allotment </b>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="received_bo_second" class="control-label">Received by BO</label>
                                <input type="text"
                                    id="received_bo_second"
                                    name="received_bo_second"
                                    class="form-control form-control-sm datetimepicker"
                                    autocomplete="off"
                                    value="<?= safeFormatDatetime($received_bo_second ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_bo_second_now" data-target="received_bo_second" <?php if(isset($received_bo_second) && $received_bo_second !== '' && strpos($received_bo_second,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_bo_second_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="return_gso_completion" class="control-label">Return to GSO for completion of Docs</label>
                                <input type="text"
                                    id="return_gso_completion"
                                    name="return_gso_completion"
                                    class="form-control form-control-sm datetimepicker"
                                    autocomplete="off"
                                    value="<?= safeFormatDatetime($return_gso_completion ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="return_gso_completion_now" data-target="return_gso_completion" <?php if(isset($return_gso_completion) && $return_gso_completion !== '' && strpos($return_gso_completion,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="return_gso_completion_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <!--------------------- ROW 9 --------------------->
                <div class="row border-top pt-3">
                    <div class="col-sm-12">
                        <b class="border-bottom border-primary">For Disbursement</b>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="received_accounting_first" class="control-label">Received by Accounting</label>
                                <input type="text"
                                   id="received_accounting_first"
                                   name="received_accounting_first"
                                   class="form-control form-control-sm datetimepicker"
                                   autocomplete="off"
                                   value="<?= safeFormatDatetime($received_accounting_first ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_accounting_first_now" data-target="received_accounting_first" <?php if(isset($received_accounting_first) && $received_accounting_first !== '' && strpos($received_accounting_first,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_accounting_first_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="received_treasury_second" class="control-label">Received by Treasury (voucher)</label>
                                <input type="text"
                                   id="received_treasury_second"
                                   name="received_treasury_second"
                                   class="form-control form-control-sm datetimepicker"
                                   autocomplete="off"
                                   value="<?= safeFormatDatetime($received_treasury_second ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_treasury_second_now" data-target="received_treasury_second" <?php if(isset($received_treasury_second) && $received_treasury_second !== '' && strpos($received_treasury_second,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_treasury_second_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="received_mo" class="control-label">Received by MO</label>
                                <input type="text"
                                    id="received_mo"
                                    name="received_mo"
                                    class="form-control form-control-sm datetimepicker"
                                    autocomplete="off"
                                    value="<?= safeFormatDatetime($received_mo ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_mo_now" data-target="received_mo" <?php if(isset($received_mo) && $received_mo !== '' && strpos($received_mo,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_mo_now">Received</label>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="received_treasury_third" class="control-label">Received by Treasury (cheque)</label>
                                <input type="text"
                                    id="received_treasury_third"
                                    name="received_treasury_third"
                                    class="form-control form-control-sm datetimepicker"
                                    autocomplete="off"
                                    value="<?= safeFormatDatetime($received_treasury_third ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_treasury_third_now" data-target="received_treasury_third" <?php if(isset($received_treasury_third) && $received_treasury_third !== '' && strpos($received_treasury_third,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_treasury_third_now">Received</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="received_admin" class="control-label">Received by Admin</label>
                                <input type="text"
                                   id="received_admin"
                                   name="received_admin"
                                   class="form-control form-control-sm datetimepicker"
                                   autocomplete="off"
                                   value="<?= safeFormatDatetime($received_admin ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_admin_now" data-target="received_admin" <?php if(isset($received_admin) && $received_admin !== '' && strpos($received_admin,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_admin_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="received_accounting_second" class="control-label">Received by Accounting (advice)</label>
                                <input type="text"
                                   id="received_accounting_second"
                                   name="received_accounting_second"
                                   class="form-control form-control-sm datetimepicker"
                                   autocomplete="off"
                                   value="<?= safeFormatDatetime($received_accounting_second ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_accounting_second_now" data-target="received_accounting_second" <?php if(isset($received_accounting_second) && $received_accounting_second !== '' && strpos($received_accounting_second,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_accounting_second_now">Received</label>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="received_treasury_fourth" class="control-label">Received by Treasury (release)</label>
                                <input type="text"
                                    id="received_treasury_fourth"
                                    name="received_treasury_fourth"
                                    class="form-control form-control-sm datetimepicker"
                                    autocomplete="off"
                                    value="<?= safeFormatDatetime($received_treasury_fourth ?? '') ?>">
                                <div class="ml-2">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input auto-timestamp" type="checkbox" id="received_treasury_fourth_now" data-target="received_treasury_fourth" <?php if(isset($received_treasury_fourth) && $received_treasury_fourth !== '' && strpos($received_treasury_fourth,'0000-00-00') === false) echo 'checked disabled'; ?>>
                                        <label class="form-check-label small" for="received_treasury_fourth_now">Received</label>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cheque_no" class="control-label">Cheque no.</label>
                            <input type="text"
                                    id="cheque_no"
                                    name="cheque_no"
                                    class="form-control form-control-sm"
                                    autocomplete="off"
                                    value="<?= htmlspecialchars($cheque_no ?? '', ENT_QUOTES) ?>">
                            <div class="form-check">
                                <!-- Always submit a default 'no' value when checkbox is unchecked -->
                                <input type="hidden" name="paid" value="no">
                                <input class="form-check-input" type="checkbox" id="paid" name="paid" value="yes" <?php if(isset($paid) && ($paid == 1 || $paid === '1' || strtolower($paid) === 'yes')) echo 'checked'; ?>>
                                <label class="form-check-label small" for="paid">Paid</label>
                            </div>
                        </div>
                    </div>
                </div>


            </form>

            <script>
                // Consolidated: toggle visibility, add/remove rows
                (function($){
                    function toggleConsolidated(){
                        var val = $('#procurement_type').val();
                        // If procurement type is 'consolidated' show consolidated blocks;
                        // otherwise (including empty/no selection) show the single section by default.
                        if(val === 'consolidated'){
                            $('#consolidated_section').show();
                            $('#consolidated_preview').show();
                            $('#single_section').hide();
                        } else {
                            // For 'single' and for empty/no selection, show single by default
                            $('#single_section').show();
                            $('#consolidated_section').hide();
                            $('#consolidated_preview').hide();
                        }
                    }

                    function bindConsolidatedControls(){
                        // Add new row (clone template row and clear values)
                        $(document).on('click', '.add-consolidated', function(e){
                            e.preventDefault();
                            var $first = $('#consolidated_rows .consolidated-row').first();
                            var $new = $first.clone();
                            // clear inputs
                            $new.find('input').val('');
                            // show remove button
                            $new.find('.remove-consolidated').show();
                            // ensure first row remove button is hidden
                            $('#consolidated_rows .consolidated-row').first().find('.remove-consolidated').hide();
                            // append
                            $('#consolidated_rows').append($new);
                            // trigger formatting if available
                            var $amt = $new.find('.amount-input');
                            if(typeof formatInput === 'function'){
                                $amt.each(function(){ formatInput(this); });
                            } else {
                                $amt.trigger('input');
                            }
                            // update grand total after adding a row
                            try{ updateConsolidatedGrandTotal(); }catch(_){ }
                        });

                        // Remove a row
                        $(document).on('click', '.remove-consolidated', function(e){
                            e.preventDefault();
                            var $row = $(this).closest('.consolidated-row');
                            // do not remove if it's the only row
                            if($('#consolidated_rows .consolidated-row').length <= 1) return;
                            $row.remove();
                            // ensure first row remove is hidden
                            $('#consolidated_rows .consolidated-row').first().find('.remove-consolidated').hide();
                            // update grand total after removing a row
                            try{ updateConsolidatedGrandTotal(); }catch(_){ }
                        });
                    }

                    $(function(){
                        $('#procurement_type').on('change', toggleConsolidated);
                        // ensure initial state (in case server-side markup didn't set it)
                        toggleConsolidated();
                        bindConsolidatedControls();
                        // ensure amount-input formatting applies to existing rows
                        if(typeof formatInput === 'function'){
                            $('#consolidated_rows .amount-input').each(function(){ formatInput(this); });
                        }
                        // compute initial grand total on load
                        try{ updateConsolidatedGrandTotal(); }catch(_){ }
                    });

                    // PHILGEPS posting toggle: require/show fields when 'With Posting', otherwise only RFQ is available
                    function togglePhilgepsFields(){
                        var val = $('#philgeps_posting').val();
                        var $section = $('#philgeps_section');
                        // field selectors inside philgeps section
                        var $received = $section.find('#received_bac_third').closest('.col-md-3');
                        var $rfqCol = $section.find('#rfq_no').closest('.col-md-3');
                        var $reposting = $section.find('#reposting').closest('.col-md-3');
                        var $returned = $section.find('#returned_gso_abstract').closest('.col-md-3');

                        if(val === 'With Posting'){
                            // show all fields and enable inputs
                            $received.show();
                            $rfqCol.show();
                            $reposting.show();
                            $returned.show();
                            $section.find('input,select,textarea').prop('disabled', false);
                            // set required where appropriate
                            $section.find('#received_bac_third, #rfq_no, #reposting, #returned_gso_abstract').prop('required', true);
                        } else {
                            // Without Posting: only RFQ should be available
                            $received.hide();
                            $reposting.hide();
                            $returned.hide();
                            // disable all controls then enable rfq only
                            $section.find('input,select,textarea').prop('disabled', true).prop('required', false);
                            $section.find('#rfq_no').prop('disabled', false).prop('required', true);
                            // clear values for hidden/disabled fields so they are not accidentally submitted
                            $section.find('#received_bac_third, #returned_gso_abstract').val('');
                            $section.find('#reposting').val('');
                        }
                    }

                    // hook change and run once on load
                    $(function(){
                        $('#philgeps_posting').on('change', togglePhilgepsFields);
                        togglePhilgepsFields();
                    });

                    /* Number formatting for amount inputs: thousand separators while typing
                       Preserves caret position. Applies to any input with class `amount-input`. */
                    function formatAmountValue(raw){
                        if(raw === null || raw === undefined) return '';
                        var s = String(raw);
                        var neg = s.charAt(0) === '-';
                        if(neg) s = s.substring(1);
                        // remove all except digits and dot
                        s = s.replace(/[^0-9.]/g,'');
                        if(s === '') return neg ? '-' : '';
                        var parts = s.split('.');
                        var intPart = parts[0] || '0';
                        // remove leading zeros but leave single 0
                        intPart = intPart.replace(/^0+(?=\d)/, '');
                        // add thousand separators
                        intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        if(parts.length > 1){
                            // keep any decimal fraction as-is (do not add separators there)
                            return (neg?'-':'') + intPart + '.' + parts.slice(1).join('.');
                        }
                        return (neg?'-':'') + intPart;
                    }

                    function formatAmountInput(el){
                        try{
                            var raw = el.value || '';
                            var start = (typeof el.selectionStart === 'number') ? el.selectionStart : raw.length;
                            // determine if caret was after decimal and count digits before caret
                            var left = raw.slice(0, start);
                            var dotIndex = raw.indexOf('.');
                            var wasAfterDecimal = dotIndex >= 0 && start > dotIndex;
                            var digitsBeforeCaret = (left.replace(/[^0-9]/g,'') || '').length;
                            var digitsAfterDecimal = 0;
                            if(wasAfterDecimal){
                                var afterPart = left.indexOf('.') >= 0 ? left.slice(left.indexOf('.')+1) : '';
                                digitsAfterDecimal = (afterPart.replace(/[^0-9]/g,'') || '').length;
                            }

                            // plain numeric (no commas)
                            var plain = raw.replace(/,/g,'');
                            var formatted = formatAmountValue(plain);
                            // set formatted value
                            el.value = formatted;

                            // now compute caret position by counting digits in formatted string
                            if(wasAfterDecimal){
                                var fDot = formatted.indexOf('.');
                                if(fDot === -1){
                                    // no decimal in formatted value — put caret at end
                                    el.setSelectionRange(formatted.length, formatted.length);
                                } else {
                                    var pos = fDot + 1;
                                    var seen = 0;
                                    for(var i = fDot + 1; i < formatted.length; i++){
                                        if(/[0-9]/.test(formatted.charAt(i))) seen++;
                                        pos = i + 1;
                                        if(seen >= digitsAfterDecimal) break;
                                    }
                                    if(pos > formatted.length) pos = formatted.length;
                                    el.setSelectionRange(pos, pos);
                                }
                            } else {
                                var pos = 0;
                                var seen = 0;
                                for(var i = 0; i < formatted.length; i++){
                                    if(/[0-9]/.test(formatted.charAt(i))) seen++;
                                    pos = i + 1;
                                    if(seen >= digitsBeforeCaret) break;
                                }
                                if(pos > formatted.length) pos = formatted.length;
                                el.setSelectionRange(pos, pos);
                            }
                        }catch(e){
                            // fallback: do nothing
                        }
                    }

                    // if no global formatInput exists, alias it so other code can call
                    if(typeof window.formatInput !== 'function'){
                        window.formatInput = function(node){ formatAmountInput(node); };
                    }

                    /* Compute consolidated grand total: sum all inputs named amount[] inside #consolidated_rows,
                       format the total with thousand separators and two decimals, and set the readonly
                       `#consolidated_grand_total` input's value. */
                    function updateConsolidatedGrandTotal(){
                        try{
                            var total = 0;
                            $('#consolidated_rows input[name="amount[]"]').each(function(){
                                var v = $(this).val() || '';
                                // strip commas
                                v = v.replace(/,/g,'');
                                if(v === '') return;
                                var n = parseFloat(v);
                                if(!isNaN(n)) total += n;
                            });
                            // format with two decimals when non-zero
                            var formatted = '';
                            if(total !== 0){
                                formatted = formatAmountValue(total.toFixed(2));
                            }
                            $('#consolidated_grand_total').val(formatted);
                        }catch(e){
                            // noop on error
                        }
                    }

                    // bind to input events for live formatting; also update consolidated grand total when consolidated rows change
                    $(document).on('input', '.amount-input', function(){
                        formatAmountInput(this);
                        var $t = $(this);
                        if($t.closest('#consolidated_rows').length || $t.attr('name') === 'amount[]'){
                            updateConsolidatedGrandTotal();
                        }
                    });

                    // strip commas before form submit so server gets plain numbers
                    $(document).on('submit', '#manage-project', function(){
                        $('.amount-input').each(function(){
                            var v = $(this).val() || '';
                            $(this).val(v.replace(/,/g,''));
                        });
                    });
                })(jQuery);
            </script>
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

/* Readonly datetime inputs: visually indicate non-editable and prevent pointer interaction */
.locked-datetime[readonly] {
    background-color: #f8f9fa !important;
    cursor: not-allowed !important;
}
/* Remarks locked style */
.locked-remarks[readonly] {
    background-color: #f8f9fa !important;
    cursor: not-allowed !important;
}

/* Readonly monetary fields (grand total, contract cost) should still show text cursor
   so users can select/copy the value even though it's not editable. */
.amount-input[readonly] {
    cursor: not-allowed !important;
}

/* Filled input indicator: light green when a field contains data */
.filled-input {
    background-color: #eaffea !important;
    border-color: #bfeeb8 !important;
}
/* Stronger selectors to ensure the green background wins over other readonly/locked styles */
input.filled-input, textarea.filled-input {
    background-color: #eaffea !important;
    border-color: #bfeeb8 !important;
}
/* Do not change the background of native/custom selects when marked filled.
   Keep the visual filled state limited to text inputs; selects will remain
   with their default background so the user can adjust this later. */
select.custom-select.filled-input, select.filled-input {
    background-color: transparent !important;
    border-color: inherit !important;
    color: inherit !important;
}
.datetimepicker.filled-input[readonly], input.locked-datetime.filled-input[readonly] {
    background-color: #eaffea !important;
    border-color: #bfeeb8 !important;
    color: #000 !important;
}
.filled-input::placeholder, textarea.filled-input::placeholder{
    color: #000 !important;
}

/* Dark-mode support: when the page or a parent container has a dark theme class,
   use a subtle transparent green + light text to remain visible on dark backgrounds */
.dark-mode input.filled-input,
.mode-dark input.filled-input,
.theme-dark input.filled-input,
.dark input.filled-input,
.dark-mode textarea.filled-input,
.mode-dark textarea.filled-input,
.theme-dark textarea.filled-input,
.dark textarea.filled-input,
.dark-mode .datetimepicker.filled-input[readonly],
.mode-dark .datetimepicker.filled-input[readonly],
.theme-dark .datetimepicker.filled-input[readonly],
.dark .datetimepicker.filled-input[readonly] {
    background-color: rgba(46,204,113,0.06) !important;
    border-color: rgba(46,204,113,0.35) !important;
    color: #eaffea !important;
}

/* Also style Select2 container when running in dark mode */
.dark-mode .select2-container--bootstrap4 .select2-selection--single.filled-input,
.mode-dark .select2-container--bootstrap4 .select2-selection--single.filled-input,
.theme-dark .select2-container--bootstrap4 .select2-selection--single.filled-input,
.dark .select2-container--bootstrap4 .select2-selection--single.filled-input{
    /* Keep Select2 container appearance unchanged for now (no green background). */
    background-color: transparent !important;
    border-color: inherit !important;
    color: inherit !important;
}

/* Ensure native selects and bootstrap custom-selects match text inputs in dark mode.
   Use stronger selectors and include the non-filled state so the select doesn't
   appear with a pale (light) background when the page theme is dark. */
.dark-mode #manage-project .custom-select,
.mode-dark #manage-project .custom-select,
.theme-dark #manage-project .custom-select,
.dark #manage-project .custom-select,
.dark-mode #manage-project select,
.mode-dark #manage-project select,
.theme-dark #manage-project select,
.dark #manage-project select {
    background-color: transparent !important;
    background-image: none !important;
    border-color: rgba(255,255,255,0.06) !important;
    color: #eaffea !important;
}

/* When a select is considered "filled" (has value), apply the same subtle
   green filled style used for inputs so visual appearance is consistent. */
.dark-mode #manage-project .custom-select.filled-input,
.mode-dark #manage-project .custom-select.filled-input,
.theme-dark #manage-project .custom-select.filled-input,
.dark #manage-project .custom-select.filled-input,
.dark-mode #manage-project select.filled-input,
.mode-dark #manage-project select.filled-input,
.theme-dark #manage-project select.filled-input,
.dark #manage-project select.filled-input {
    /* Do not apply green background to selects in dark mode; keep default look. */
    background-color: transparent !important;
    border-color: inherit !important;
    color: inherit !important;
}

/* Also ensure Select2 single-selection containers (Bootstrap4 theme) look the same */
.dark-mode .select2-container--bootstrap4 .select2-selection--single,
.mode-dark .select2-container--bootstrap4 .select2-selection--single,
.theme-dark .select2-container--bootstrap4 .select2-selection--single,
.dark .select2-container--bootstrap4 .select2-selection--single {
    background-color: transparent !important;
    color: #eaffea !important;
    border-color: rgba(255,255,255,0.06) !important;
}
</style>

<script>
// Helper: return current datetime in 'Y/m/d H:i' format
function nowFormatted(){
    var d = new Date();
    function z(n){return n<10?'0'+n:n}
    var year = d.getFullYear();
    var month = z(d.getMonth()+1);
    var day = z(d.getDate());
    var hours = z(d.getHours());
    var mins = z(d.getMinutes());
    return year + '/' + month + '/' + day + ' ' + hours + ':' + mins;
}

// Checkbox handler: when checked set target input to now and make it readonly so it's still submitted.
$(document).on('change', '.auto-timestamp', function(){
    var targetId = $(this).data('target');
    if(!targetId) return;
    var $target = $('#' + targetId);
    if($(this).is(':checked')){
        // set the timestamp; keep inputs readonly so only checkbox controls the value
        $target.val(nowFormatted()).addClass('auto-filled');
        // Update the filled state so CSS (e.g. .filled-input) is applied immediately
        try{ updateFilledState($target); }catch(_){ $target.trigger('change'); }
    }else{
        // clear the value when unchecked; remain readonly (only checkbox sets it)
        $target.removeClass('auto-filled');
        if($target.val() && $target.val().length > 0){
            $target.val('');
        }
        // Ensure filled state is updated after clearing
        try{ updateFilledState($target); }catch(_){ $target.trigger('change'); }
    }
});

// If user manually edits a datetime input, uncheck the corresponding checkbox
$(document).on('input', '.datetimepicker', function(){
    var id = $(this).attr('id');
    if(!id) return;
    var cb = $('#' + id + '_now');
    if(cb.length && cb.is(':checked')){
        cb.prop('checked', false);
    }
});

// Make all datetimepicker inputs readonly by default so they can only be set via checkbox
$(function () {
    $('.datetimepicker').each(function(){
        // ensure input is writable by script but not editable by user
        $(this).prop('readonly', true).addClass('locked-datetime').attr('tabindex','-1');
    });
    // Prevent user interaction: block mouse/touch focus and keyboard typing on locked datetime inputs
    $(document).on('mousedown touchstart', '.datetimepicker.locked-datetime', function(e){
        e.preventDefault();
        // blur if somehow focused
        try{ this.blur(); }catch(_){ }
        return false;
    });
    // Prevent programmatic focus from keyboard
    $(document).on('focusin', '.datetimepicker.locked-datetime', function(e){
        try{ this.blur(); }catch(_){ }
    });
    // Prevent typing into the field
    $(document).on('keydown', '.datetimepicker.locked-datetime', function(e){
        e.preventDefault();
        return false;
    });
});

// Helper to mark inputs/selects/textareas as filled (adds/removes .filled-input)
function updateFilledState(el){
    try{
        var $el = $(el);
        var tag = ($el.prop('tagName')||'').toLowerCase();
        var type = ($el.attr('type')||'').toLowerCase();
        var hasValue = false;
        if(tag === 'input'){
            if(type === 'checkbox' || type === 'radio'){
                hasValue = $el.is(':checked');
            } else {
                hasValue = $.trim($el.val()||'') !== '';
            }
        } else if(tag === 'select' || tag === 'textarea'){
            var v = $el.val();
            if(typeof v === 'string') hasValue = $.trim(v) !== '';
            else if(Array.isArray(v)) hasValue = v.length > 0;
        }
        // Special case: for numeric monetary fields, consider 0 (or 0.00) as empty
        try{
            var name = ($el.attr('name')||'').toLowerCase();
            var id = ($el.attr('id')||'').toLowerCase();
            if(tag === 'input' && (name === 'amount' || name === 'contract_cost' || id === 'contract_cost')){
                var raw = ($el.val()||'').toString().replace(/,/g,'');
                var n = parseFloat(raw);
                if(!isNaN(n) && Math.abs(n) === 0){
                    hasValue = false;
                }
            }
        }catch(_){ }
        // Toggle class on the element itself only. Avoid styling the whole form-group.
        $el.toggleClass('filled-input', !!hasValue);
        // If this select is enhanced by Select2, also toggle the class on its visible container
        try{
            if($el.is('select')){
                var $s2 = $el.next('.select2-container');
                if($s2 && $s2.length){
                    $s2.toggleClass('filled-input', !!hasValue);
                    // Also mark the Select2 selection element (Bootstrap 4 theme)
                    $s2.find('.select2-selection--single').toggleClass('filled-input', !!hasValue);
                }
            }
        }catch(_){ }
    }catch(e){ /* ignore */ }
}

// Delegated handlers: update filled state on user input/change anywhere in the form
$(document).on('input change', '#manage-project input, #manage-project select, #manage-project textarea', function(){
    updateFilledState(this);
});

    $('#manage-project').submit(function (e) {
        e.preventDefault()

        // Client-side validation for required fields
        var pr_no = $.trim($('[name="pr_no"]').val() || '');
        var particulars = $.trim($('[name="particulars"]').val() || '');
        var start_date = $.trim($('[name="start_date"]').val() || '');

        var missing = [];
        if (!pr_no) missing.push('PR No.');
        if (!particulars) missing.push('Particulars');
        if (!start_date) missing.push('Start Date');

        if (missing.length > 0) {
            var msg = 'Please fill the following required fields: ' + missing.join(', ');
            alert_toast(msg, 'warning');
            return false;
        }

        // Ensure numeric fields are sent without thousands separators
        $('[name="amount"], #contract_cost').each(function(){
            var $t = $(this);
            if(typeof $t.val === 'function'){
                $t.val(($t.val()||'').replace(/,/g,''));
            }
        });
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

    // PHILGEPS section: keep visible regardless of `philgeps_posting` selection
    function togglePhilgepsSection() {
        // Ensure the PHILGEPS section is always shown. Do not clear or hide its inputs.
        $('#philgeps_section').show();
    }

    // Initialize visibility and bind change event
    $(function () {
        togglePhilgepsSection();
        $('#philgeps_posting').on('change', function () {
            togglePhilgepsSection();
        });

        // Remarks availability: toggle based on procurement type
        function toggleRemarksAvailability(){
            var val = $('#procurement_type').val();
            var $remarks = $('#remarks_pr_no');
            if(typeof val !== 'undefined' && val === 'single'){
                // make readonly and clear value
                $remarks.prop('readonly', true).addClass('locked-remarks');
                $remarks.val('');
                $remarks.attr('placeholder', 'Not available for Single procurement');
            } else {
                $remarks.prop('readonly', false).removeClass('locked-remarks');
                $remarks.attr('placeholder', '');
            }
        }
        // initialize on load
        toggleRemarksAvailability();
        // bind change
        $('#procurement_type').on('change', function(){
            toggleRemarksAvailability();
        });
    });

    // --- Begin: Improved thousand-separator formatting for numeric inputs ---
    (function(){
        function formatNumberString(val){
            if(typeof val !== 'string') return val;
            var negative = val.charAt(0) === '-';
            if(negative) val = val.slice(1);
            // keep track if user typed a trailing dot
            var hasTrailingDot = val.charAt(val.length-1) === '.';
            // collapse multiple dots: keep first
            var firstDotIndex = val.indexOf('.');
            var raw = firstDotIndex >= 0 ? val.slice(0, firstDotIndex) + '.' + val.slice(firstDotIndex+1).replace(/\./g,'') : val;
            var parts = raw.split('.');
            var intPart = parts[0].replace(/[^0-9]/g,'');
            // allow empty intPart (user may type ".95")
            var withCommas = intPart === '' ? '' : intPart.replace(/\B(?=(\d{3})+(?!\d))/g,',');
            var decPart = parts.length > 1 ? parts.slice(1).join('').replace(/[^0-9]/g,'') : '';
            var out = withCommas + (decPart.length ? '.' + decPart : (hasTrailingDot ? '.' : ''));
            if(negative) out = '-' + out;
            return out;
        }

        function setCaretByDigitCount(el, digitsBeforeCaret, digitsAfterDecimal, wasAfterDecimal){
            var v = el.value;
            var i = 0, digitsSeen = 0;
            if(!wasAfterDecimal){
                for(i=0;i<v.length;i++){
                    if(v.charAt(i).match(/\d/)) digitsSeen++;
                    if(digitsSeen >= digitsBeforeCaret) { i = i+1; break; }
                }
                if(digitsBeforeCaret === 0) i = 0;
                if(i > v.length) i = v.length;
                el.setSelectionRange(i,i);
                return;
            } else {
                var dotPos = v.indexOf('.');
                if(dotPos === -1){
                    // no decimal in formatted value; place at end
                    el.setSelectionRange(v.length, v.length);
                    return;
                }
                // move to dot + digitsAfterDecimal
                var pos = dotPos + 1;
                var seen = 0;
                for(i = dotPos+1; i < v.length; i++){
                    if(v.charAt(i).match(/\d/)) seen++;
                    if(seen >= digitsAfterDecimal){ pos = i+1; break; }
                }
                // if digitsAfterDecimal is 0 and user was just after dot, place right after dot
                if(digitsAfterDecimal === 0) pos = dotPos + 1;
                if(pos > v.length) pos = v.length;
                el.setSelectionRange(pos,pos);
                return;
            }
        }

        function formatInput(el){
            try{
                var raw = el.value || '';
                var caret = el.selectionStart || 0;
                var dotIndex = raw.indexOf('.');
                var wasAfterDecimal = dotIndex >= 0 && caret > dotIndex;
                var left = raw.slice(0, caret);
                var digitsBeforeCaret = (left.split('.')[0].match(/\d/g)||[]).length;
                var digitsAfterDecimal = 0;
                if(wasAfterDecimal){
                    var after = left.indexOf('.') >=0 ? left.slice(left.indexOf('.')+1) : '';
                    digitsAfterDecimal = (after.match(/\d/g)||[]).length;
                }
                var stripped = raw.replace(/,/g,'');
                var newVal = formatNumberString(stripped);
                el.value = newVal;
                // set caret based on digit counts
                setCaretByDigitCount(el, digitsBeforeCaret, digitsAfterDecimal, wasAfterDecimal);
            }catch(e){
                // ignore
            }
        }

        $(function(){
            $('#manage-project').on('input', '[name="amount"], #contract_cost', function(e){
                formatInput(this);
            });

            // Format initial numeric values on load so separators are visible when editing
            $('[name="amount"], #contract_cost').each(function(){
                var v = $(this).val() || '';
                if(v !== ''){
                    // ensure consistent base value (no existing commas) then trigger input
                    $(this).val(v.replace(/,/g,''));
                    $(this).trigger('input');
                }
            });

            // After formatting initial numeric values, mark all prefilled fields
            $('#manage-project').find('input,select,textarea').each(function(){
                updateFilledState(this);
            });

            // Strip commas before actual submit (the ajax submit handler will be triggered afterwards)
            $('#manage-project').on('submit', function(){
                $('[name="amount"], #contract_cost').each(function(){
                    var $t = $(this);
                    if(typeof $t.val === 'function'){
                        $t.val(($t.val()||'').replace(/,/g,''));
                    }
                });
            });
        });
    })();
    // --- End: Improved thousand-separator formatting ---
</script>
