<?php include 'db_connect.php' ?>

<div class="col-md-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <b>Document Progress</b>
            <div class="card-tools">
                <button class="btn btn-flat btn-sm bg-gradient-success btn-success" id="print"><i class="fa fa-print"></i> Print</button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive" id="printable">
                <table class="table m-0 table-bordered">
                    <!-- <colgroup>
                        <col width="5%">
                        <col width="75%">
                        <col width="10%">
                    </colgroup> -->
                    <thead>
                        <th>#</th>
                        <th>Document</th>
                        <th class="text-center">Status</th>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $stat = array("Pending", "Started", "On-Progress", "On-Hold", "Over Due", "Done");
                        include_once 'workflow_helper.php';
                        // Show documents to all users (both Admin and Employee)
                        $where = "";
                        $qry = $conn->query("SELECT * FROM project_list $where order by id asc");
                        while ($row = $qry->fetch_assoc()):
                            // Ensure counters exist to avoid undefined variable notices
                            $prod = 0;
                            $cprog = 0;
                            ?>
                            <tr>
                                <td>
                                    <?php echo $i++ ?>
                                </td>
                                <td>
                                    <a>
                                        <?php echo ucwords($row['particulars']) ?>
                                    </a>
                                    <br>
                                </td>
                                <td class="project-state text-center">
                                    <?php
                                    // Compute workflow stage dynamically from filled fields
                                    $ws = compute_workflow_stage($conn, $row);
                                    echo "<span class='badge {$ws['badge']}'>" . htmlspecialchars($ws['stage'], ENT_QUOTES) . "</span>";
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $('#print').click(function () {
        start_load()
        var _h = $('head').clone()
        var _p = $('#printable').clone()
        var _d = "<p class='text-center'><b>Project Progress Report as of (<?php echo date("F d, Y") ?>)</b></p>"
        _p.prepend(_d)
        _p.prepend(_h)
        var nw = window.open("", "", "width=900,height=600")
        nw.document.write(_p.html())
        nw.document.close()
        nw.print()
        setTimeout(function () {
            nw.close()
            end_load()
        }, 750)
    })
</script>
