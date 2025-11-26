<?php include('db_connect.php') ?>
<?php
    $twhere = "";
    if ($_SESSION['login_type'] != 1) {
        $twhere = "  ";
    }
?>

<!-- Info boxes -->
<div class="col-12">
    <div class="card">
        <div class="card-body">
            Welcome <?php echo $_SESSION['login_name'] ?>!
        </div>
    </div>
</div>
<hr>
<?php
    // Show documents to all users (both Admin and Employee).
    $where = "";

    // Secondary filter (if used) also cleared so users see all documents
    $where2 = "";
?>

<div class="row">
    <div class="col-md-8">
        <div class="card card-outline card-success">
            <div class="card-header">
                <b>Document Progress</b>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0 table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="75%">
                            <col width="15%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <th>#</th>
                            <th>Document</th>
                            <th>Status</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $stat = array("Pending", "Started", "On-Progress", "On-Hold", "Over Due", "Done");
                            // Show documents to all users (both Admin and Employee)
                            $where = "";
                            $qry = $conn->query("SELECT * FROM project_list $where order by id asc");
                            while ($row = $qry->fetch_assoc()):
                                $prog = 0;
                                $prog = $prog > 0 ? number_format($prog, 2) : $prog;
                                if ($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
                                    if ($prod > 0 || $cprog > 0)
                                        $row['status'] = 2;
                                    else
                                        $row['status'] = 1;
                                elseif ($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
                                    $row['status'] = 4;
                                endif;
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
                                    <td class="project-state">
                                        <?php
                                        if ($stat[$row['status']] == 'Pending') {
                                            echo "<span class='badge badge-secondary'>{$stat[$row['status']]}</span>";
                                        } elseif ($stat[$row['status']] == 'Started') {
                                            echo "<span class='badge badge-primary'>{$stat[$row['status']]}</span>";
                                        } elseif ($stat[$row['status']] == 'On-Progress') {
                                            echo "<span class='badge badge-info'>{$stat[$row['status']]}</span>";
                                        } elseif ($stat[$row['status']] == 'On-Hold') {
                                            echo "<span class='badge badge-warning'>{$stat[$row['status']]}</span>";
                                        } elseif ($stat[$row['status']] == 'Over Due') {
                                            echo "<span class='badge badge-danger'>{$stat[$row['status']]}</span>";
                                        } elseif ($stat[$row['status']] == 'Done') {
                                            echo "<span class='badge badge-success'>{$stat[$row['status']]}</span>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="./index.php?page=view_document&id=<?php echo $row['id'] ?>">
                                            <i class="fas fa-folder">
                                            </i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-12">
                <div class="small-box bg-light shadow-sm border">
                    <div class="inner">
                        <h3><?php echo $conn->query("SELECT * FROM project_list $where")->num_rows; ?></h3>

                        <p>Total Docuement</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-layer-group"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
