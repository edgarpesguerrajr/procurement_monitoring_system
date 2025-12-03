<?php
session_start();
ini_set('display_errors', 1);

Class Action
{
    private $db;

    public function __construct()
    {
        ob_start();
        include 'db_connect.php';

        $this->db = $conn;
    }

    function __destruct()
    {
        $this->db->close();
        ob_end_flush();
    }

    /**
     * Ensure notifications table exists. Run safely and ignore failures.
     */
    private function ensureNotificationsTableExists(){
        try{
            $sql = "CREATE TABLE IF NOT EXISTS notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                actor_id INT NOT NULL,
                project_id INT NOT NULL,
                message TEXT,
                is_read TINYINT(1) DEFAULT 0,
                created_at DATETIME
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            @$this->db->query($sql);
        }catch(Exception $_){
            // ignore
        }
    }

    function login()
    {
        extract($_POST);
        $qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where email = '" . $email . "' and password = '" . md5($password) . "'  ");
        if ($qry->num_rows > 0) {
            foreach ($qry->fetch_array() as $key => $value) {
                if ($key != 'password' && !is_numeric($key))
                    $_SESSION['login_' . $key] = $value;
            }
            return 1;
        } else {
            return 2;
        }
    }

    function logout()
    {
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        header("location:login.php");
    }

    function login2()
    {
        extract($_POST);
        $qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '" . $student_code . "' ");
        if ($qry->num_rows > 0) {
            foreach ($qry->fetch_array() as $key => $value) {
                if ($key != 'password' && !is_numeric($key))
                    $_SESSION['rs_' . $key] = $value;
            }
            return 1;
        } else {
            return 3;
        }
    }

    function save_user()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        if (!empty($password)) {
            $data .= ", password=md5('$password') ";
        }
        $check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";
        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO users set $data");
        } else {
            $save = $this->db->query("UPDATE users set $data where id = $id");
        }
        if ($save) {
            return 1;
        }
    }

    function signup()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass')) && !is_numeric($k)) {
                if ($k == 'password') {
                    if (empty($v))
                        continue;
                    $v = md5($v);
                }
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        $check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";
        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO users set $data");
        } else {
            $save = $this->db->query("UPDATE users set $data where id = $id");
        }
        if ($save) {
            if (empty($id))
                $id = $this->db->insert_id;
            foreach ($_POST as $key => $value) {
                if (!in_array($key, array('id', 'cpass', 'password')) && !is_numeric($key))
                    $_SESSION['login_' . $key] = $value;
            }
            $_SESSION['login_id'] = $id;
            if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
                $_SESSION['login_avatar'] = $fname;
            return 1;
        }
    }

    function update_user()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass', 'table', 'password')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        $check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";
        }
        if (!empty($password))
            $data .= " ,password=md5('$password') ";
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO users set $data");
        } else {
            $save = $this->db->query("UPDATE users set $data where id = $id");
        }
        if ($save) {
            foreach ($_POST as $key => $value) {
                if ($key != 'password' && !is_numeric($key))
                    $_SESSION['login_' . $key] = $value;
            }
            if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
                $_SESSION['login_avatar'] = $fname;
            return 1;
        }
    }

    function delete_user()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM users where id = " . $id);
        if ($delete)
            return 1;
    }

    function save_system_settings()
    {
        extract($_POST);
        $data = '';
        foreach ($_POST as $k => $v) {
            if (!is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        if ($_FILES['cover']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['cover']['name'];
            $move = move_uploaded_file($_FILES['cover']['tmp_name'], '../assets/uploads/' . $fname);
            $data .= ", cover_img = '$fname' ";
        }
        $chk = $this->db->query("SELECT * FROM system_settings");
        if ($chk->num_rows > 0) {
            $save = $this->db->query("UPDATE system_settings set $data where id =" . $chk->fetch_array()['id']);
        } else {
            $save = $this->db->query("INSERT INTO system_settings set $data");
        }
        if ($save) {
            foreach ($_POST as $k => $v) {
                if (!is_numeric($k)) {
                    $_SESSION['system'][$k] = $v;
                }
            }
            if ($_FILES['cover']['tmp_name'] != '') {
                $_SESSION['system']['cover_img'] = $fname;
            }
            return 1;
        }
    }

    function save_image()
    {
        extract($_FILES['file']);
        if (!empty($tmp_name)) {
            $fname = strtotime(date("Y-m-d H:i")) . "_" . (str_replace(" ", "-", $name));
            $move = move_uploaded_file($tmp_name, 'assets/uploads/' . $fname);
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
            $hostName = $_SERVER['HTTP_HOST'];
            $path = explode('/', $_SERVER['PHP_SELF']);
            $currentPath = '/' . $path[1];
            if ($move) {
                return $protocol . '://' . $hostName . $currentPath . '/assets/uploads/' . $fname;
            }
        }
    }

    function save_project()
    {
        extract($_POST);
        // Normalize POST for procurement-type-specific handling:
        // - If procurement_type is 'consolidated', ensure the top-level `particulars`
        //   is saved as a scalar string (the single input above consolidated rows).
        // - If procurement_type is not 'consolidated' (e.g. 'single'), coerce any
        //   array inputs that might collide (from client DOM) into scalar values
        //   so they are stored correctly in `project_list` and do not get
        //   JSON-encoded as arrays.
        $__proc_type = isset($_POST['procurement_type']) ? strtolower(trim((string)$_POST['procurement_type'])) : '';
        if ($__proc_type === 'consolidated') {
            if (isset($_POST['particulars']) && is_array($_POST['particulars'])) {
                // pick the first non-empty item (top-level particulars expected first)
                $first = '';
                foreach ($_POST['particulars'] as $pv) {
                    if (is_scalar($pv) && trim((string)$pv) !== '') {
                        $first = $pv;
                        break;
                    }
                }
                $_POST['particulars'] = $first;
                // make extracted variable reflect the change for downstream code
                if (isset($particulars)) $particulars = $_POST['particulars'];
            }
            // For consolidated, keep any amount[] arrays intact — syncConsolidatedRows
            // will sum them and update project_list.amount accordingly.
        } else {
            // Single (or other): ensure amount is scalar and remove any arrays that
            // belong to consolidated rows to avoid them being json-encoded into
            // legacy project_list columns.
            if (isset($_POST['amount']) && is_array($_POST['amount'])) {
                $first = '';
                foreach ($_POST['amount'] as $av) {
                    if (is_scalar($av) && trim((string)$av) !== '') { $first = $av; break; }
                }
                $_POST['amount'] = $first;
                if (isset($amount)) $amount = $_POST['amount'];
            }
            // remove array-form consolidated inputs if present
            if (isset($_POST['pr_no']) && is_array($_POST['pr_no'])) unset($_POST['pr_no']);
            if (isset($_POST['particulars']) && is_array($_POST['particulars'])) {
                // if an array was present, coerce to first element as top-level particulars
                $first = '';
                foreach ($_POST['particulars'] as $pv) {
                    if (is_scalar($pv) && trim((string)$pv) !== '') { $first = $pv; break; }
                }
                $_POST['particulars'] = $first;
                if (isset($particulars)) $particulars = $first;
            }
            if (isset($_POST['amount']) && is_array($_POST['amount'])) unset($_POST['amount']);
        }
        // Server-side required-field checks removed to allow saving documents
        // even when PR No., Particulars, or Start Date are empty. Client-side
        // validation was also removed; perform any necessary validation elsewhere.
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
                // Skip per-row consolidated arrays so they are not included as
                // columns in the `project_list` INSERT/UPDATE (project_list
                // doesn't have `particular` or array columns for consolidated rows).
                if (is_array($v) && in_array($k, array('pr_no', 'amount', 'particular', 'particulars'))) {
                    continue;
                }
                // If the POST value is an array (for other legacy usages),
                // encode it as JSON so it can be safely stored in a text field.
                if (is_array($v)) {
                    $v = json_encode($v);
                }
                // Preserve description HTML entities
                if ($k == 'description' && is_string($v))
                    $v = htmlentities(str_replace("'", "&#x2019;", $v));
                // Normalize datetime inputs from 'Y/m/d H:i' -> 'Y-m-d H:i:s' when possible
                if (is_string($v) && preg_match('/^\d{4}\/\d{2}\/\d{2} \d{2}:\d{2}$/', $v)) {
                    $dt = DateTime::createFromFormat('Y/m/d H:i', $v);
                    if ($dt) $v = $dt->format('Y-m-d H:i:s');
                }
                if (empty($data)) {
                    $data .= " $k='" . $this->db->real_escape_string($v) . "' ";
                } else {
                    $data .= ", $k='" . $this->db->real_escape_string($v) . "' ";
                }
            }
        }
        if (isset($user_ids)) {
            $data .= ", user_ids='" . implode(',', $user_ids) . "' ";
        }
        // Ensure top-level `particulars` is present in the project_list data.
        // Some form payload shapes previously caused this key to be omitted
        // when building $data; if missing, explicitly append it using the
        // scalar $particulars or $_POST value (prefer scalar string). Append
        // even when it's an empty string so the parent `project_list.particulars`
        // always reflects the top-level Document field.
        if (!preg_match('/\bparticulars\s*=\s*/i', $data)) {
            if (isset($_POST['particulars']) && !is_array($_POST['particulars'])) {
                $pval = $_POST['particulars'];
            } elseif (isset($particulars) && is_scalar($particulars)) {
                $pval = $particulars;
            } else {
                $pval = null;
            }
            if ($pval !== null) {
                if (empty($data)) {
                    $data = " particulars='" . $this->db->real_escape_string((string)$pval) . "' ";
                } else {
                    $data .= ", particulars='" . $this->db->real_escape_string((string)$pval) . "' ";
                }
            }
        }

        // Ensure top-level `pr_no` is present in the project_list data for Single
        // procurements. Per-row `pr_no[]` are skipped earlier and handled by
        // syncConsolidatedRows(). Here we will coerce/append a scalar `pr_no`
        // (or explicit NULL) depending on procurement type below.

        // At this point we've built the generic $data from POST. Now enforce
        // procurement-type-specific parent-field behavior required by the
        // specification:
        // - Single: `pr_no` = top-level PR No. (or NULL), `amount` = top-level Amount (or NULL), `particulars` = Document
        // - Consolidated: `pr_no` = NULL, `amount` = grand total (set by sync), `particulars` = Document
        $ptype = isset($_POST['procurement_type']) ? strtolower(trim((string)$_POST['procurement_type'])) : '';

        // Helper to append clause to $data properly (handles empty $data)
        $append = function(&$d, $clause) {
            if (empty($d)) {
                $d = " $clause ";
            } else {
                $d .= ", $clause ";
            }
        };

        if ($ptype === 'consolidated') {
            // remove any existing pr_no / amount assignments to avoid duplicates
            $data = preg_replace("/\,?\s*pr_no\s*=\s*'[^']*'\s*/i", "", $data);
            $data = preg_replace("/\,?\s*amount\s*=\s*'[^']*'\s*/i", "", $data);
            // ensure pr_no is explicit NULL for consolidated
            $append($data, "pr_no=NULL");
            // ensure amount is NULL for now; syncConsolidatedRows will set grand total
            $append($data, "amount=NULL");
            // ensure particulars uses the top-level Document field
            $doc = null;
            if (isset($_POST['particulars']) && !is_array($_POST['particulars'])) {
                $doc = $_POST['particulars'];
            } elseif (isset($particulars) && is_scalar($particulars)) {
                $doc = $particulars;
            }
            if ($doc !== null) {
                $data = preg_replace("/\,?\s*particulars\s*=\s*'[^']*'\s*/i", "", $data);
                $append($data, "particulars='" . $this->db->real_escape_string((string)$doc) . "'");
            }
        } else {
            // Single (or other): ensure parent fields reflect top-level scalars
            // PR No
            $pno = null;
            if (isset($_POST['pr_no']) && !is_array($_POST['pr_no'])) {
                $pno = $_POST['pr_no'];
            } elseif (isset($pr_no) && is_scalar($pr_no)) {
                $pno = $pr_no;
            }
            // remove any existing pr_no clause
            $data = preg_replace("/\,?\s*pr_no\s*=\s*'[^']*'\s*/i", "", $data);
            if ($pno !== null && trim((string)$pno) !== '') {
                $append($data, "pr_no='" . $this->db->real_escape_string((string)$pno) . "'");
            } else {
                $append($data, "pr_no=NULL");
            }

            // Amount: prefer top-level scalar; normalize by stripping commas and formatting
            $amtVal = null;
            if (isset($_POST['amount']) && !is_array($_POST['amount'])) {
                $amtVal = $_POST['amount'];
            } elseif (isset($amount) && is_scalar($amount)) {
                $amtVal = $amount;
            }
            $data = preg_replace("/\,?\s*amount\s*=\s*'[^']*'\s*/i", "", $data);
            if ($amtVal !== null) {
                $amtClean = is_string($amtVal) ? str_replace(',','', trim($amtVal)) : $amtVal;
                if ($amtClean !== '' && is_numeric($amtClean)) {
                    $fmt = number_format((float)$amtClean, 2, '.', '');
                    $append($data, "amount='" . $this->db->real_escape_string($fmt) . "'");
                } else {
                    $append($data, "amount=NULL");
                }
            }
            // Ensure particulars uses the top-level Document field (already appended earlier if present)
        }
        // Insert or update and return the record id on success
        if (empty($id)) {
            $query = "INSERT INTO project_list set $data";
            try {
                $save = $this->db->query($query);
            } catch (Exception $e) {
                return 'DB Error: ' . $e->getMessage() . ' -- Query: ' . $query;
            }
            if ($save) {
                $newId = $this->db->insert_id;
                // If consolidated, sync rows; otherwise remove any existing consolidated rows
                // so switching to Single will clear child rows.
                $ptype = isset($_POST['procurement_type']) ? trim((string)$_POST['procurement_type']) : '';
                if (strtolower($ptype) === 'consolidated') {
                    try { $this->syncConsolidatedRows($newId); } catch (Exception $_) { }
                } else {
                    try { $this->db->query("DELETE FROM consolidated WHERE project_id = {$newId}"); } catch (Exception $_) { }
                }
                // Create notifications to inform other users that this document was created
                if (isset($_SESSION['login_id'])) {
                    $actor_id = intval($_SESSION['login_id']);
                    $actor_q = $this->db->query("SELECT concat(firstname,' ',lastname) as name FROM users WHERE id = {$actor_id} LIMIT 1");
                    $actor = ($actor_q && $actor_q->num_rows > 0) ? $actor_q->fetch_array()['name'] : 'User';
                    // determine a human-friendly document label
                    $doc_label = '';
                    $pqr = $this->db->query("SELECT particulars, pr_no FROM project_list WHERE id = " . intval($newId) . " LIMIT 1");
                    if ($pqr && $pqr->num_rows > 0) {
                        $prow = $pqr->fetch_assoc();
                        $doc_label = trim((string)($prow['particulars'] ?? ''));
                        if ($doc_label === '') {
                            $doc_label = trim((string)($prow['pr_no'] ?? ''));
                        }
                    }
                    if ($doc_label === '') {
                        $cres = $this->db->query("SELECT pr_no FROM consolidated WHERE project_id = " . intval($newId) . " AND pr_no <> '' LIMIT 1");
                        if ($cres && $cres->num_rows > 0) {
                            $doc_label = trim((string)$cres->fetch_assoc()['pr_no']);
                        }
                    }
                    if ($doc_label === '') $doc_label = 'Document #' . intval($newId);

                    $dt_n = date('Y-m-d H:i:s');
                    $message = $this->db->real_escape_string("{$actor} has an update in {$doc_label}");
                    // ensure notifications table exists (safe)
                    $this->ensureNotificationsTableExists();
                    // notify users, but ignore failures
                    try{
                        $users = $this->db->query("SELECT id FROM users");
                        if ($users) {
                            while ($u = $users->fetch_assoc()) {
                                $rid = intval($u['id']);
                                if ($rid === $actor_id) continue;
                                @$this->db->query("INSERT INTO notifications (user_id, actor_id, project_id, message, is_read, created_at) VALUES ({$rid}, {$actor_id}, " . intval($newId) . ", '{$message}', 0, '{$dt_n}')");
                            }
                        }
                    }catch(Exception $_){ }
                }
            } else {
                return 'DB Error: ' . $this->db->error . ' -- Query: ' . $query;
            }
        } else {
            $id = intval($id);
            $query = "UPDATE project_list set $data where id = $id";
            try {
                $save = $this->db->query($query);
            } catch (Exception $e) {
                return 'DB Error: ' . $e->getMessage() . ' -- Query: ' . $query;
            }
            if ($save) {
                // If consolidated, sync rows; otherwise remove existing consolidated rows
                // for this project to reflect the switch back to Single procurement.
                $ptype = isset($_POST['procurement_type']) ? trim((string)$_POST['procurement_type']) : '';
                if (strtolower($ptype) === 'consolidated') {
                    try { $this->syncConsolidatedRows($id); } catch (Exception $_) { }
                } else {
                    try { $this->db->query("DELETE FROM consolidated WHERE project_id = {$id}"); } catch (Exception $_) { }
                }
                // Create notifications to inform other users that this document was updated
                if (isset($_SESSION['login_id'])) {
                    $actor_id = intval($_SESSION['login_id']);
                    $actor_q = $this->db->query("SELECT concat(firstname,' ',lastname) as name FROM users WHERE id = {$actor_id} LIMIT 1");
                    $actor = ($actor_q && $actor_q->num_rows > 0) ? $actor_q->fetch_array()['name'] : 'User';
                    // determine a human-friendly document label
                    $doc_label = '';
                    $pqr = $this->db->query("SELECT particulars, pr_no FROM project_list WHERE id = " . intval($id) . " LIMIT 1");
                    if ($pqr && $pqr->num_rows > 0) {
                        $prow = $pqr->fetch_assoc();
                        $doc_label = trim((string)($prow['particulars'] ?? ''));
                        if ($doc_label === '') {
                            $doc_label = trim((string)($prow['pr_no'] ?? ''));
                        }
                    }
                    if ($doc_label === '') {
                        $cres = $this->db->query("SELECT pr_no FROM consolidated WHERE project_id = " . intval($id) . " AND pr_no <> '' LIMIT 1");
                        if ($cres && $cres->num_rows > 0) {
                            $doc_label = trim((string)$cres->fetch_assoc()['pr_no']);
                        }
                    }
                    if ($doc_label === '') $doc_label = 'Document #' . intval($id);

                    $dt_n = date('Y-m-d H:i:s');
                    $message = $this->db->real_escape_string("{$actor} has an update in {$doc_label}");
                    // ensure notifications table exists (safe)
                    $this->ensureNotificationsTableExists();
                    // notify users, but ignore failures
                    try{
                        $users = $this->db->query("SELECT id FROM users");
                        if ($users) {
                            while ($u = $users->fetch_assoc()) {
                                $rid = intval($u['id']);
                                if ($rid === $actor_id) continue;
                                @$this->db->query("INSERT INTO notifications (user_id, actor_id, project_id, message, is_read, created_at) VALUES ({$rid}, {$actor_id}, " . intval($id) . ", '{$message}', 0, '{$dt_n}')");
                            }
                        }
                    }catch(Exception $_){ }
                }
                return intval($id);
            } else {
                return 'DB Error: ' . $this->db->error . ' -- Query: ' . $query;
            }
        }
        return 0;
    }

    function delete_project()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM project_list where id = $id");
        if ($delete) {
            return 1;
        }
    }

    function save_progress()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id')) && !is_numeric($k)) {
                if ($k == 'comment')
                    $v = htmlentities(str_replace("'", "&#x2019;", $v));
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        $dur = abs(strtotime("2020-01-01 " . $end_time)) - abs(strtotime("2020-01-01 " . $start_time));
        $dur = $dur / (60 * 60);
        $data .= ", time_rendered='$dur' ";
        // echo "INSERT INTO user_productivity set $data"; exit;
        if (empty($id)) {
            $data .= ", user_id={$_SESSION['login_id']} ";
            $save = $this->db->query("INSERT INTO user_productivity set $data");
        } else {
            $save = $this->db->query("UPDATE user_productivity set $data where id = $id");
        }
        if ($save) {
            return 1;
        }
    }

    function delete_progress()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM user_productivity where id = $id");
        if ($delete) {
            return 1;
        }
    }

    function save_comment()
    {
        extract($_POST);
        if (!isset($project_id) || empty($project_id) || !isset($_SESSION['login_id']))
            return 0;
        // Preserve special characters: escape for SQL but store raw text (no HTML entity conversion)
        $comment = isset($comment) ? $this->db->real_escape_string($comment) : '';
        $uid = $_SESSION['login_id'];
        $dt = date('Y-m-d H:i:s');
        $save = $this->db->query("INSERT INTO comments (project_id,user_id,comment,date_created) VALUES ('{$project_id}','{$uid}','{$comment}','{$dt}')");
        if ($save) {
            // Ensure notifications table exists (safe)
            $this->ensureNotificationsTableExists();

            // Build notification message: "[User] has a comment in [document]"
            $actor_q = $this->db->query("SELECT concat(firstname,' ',lastname) as name FROM users WHERE id = {$uid} LIMIT 1");
            $actor = ($actor_q && $actor_q->num_rows > 0) ? $actor_q->fetch_array()['name'] : 'User';

            // determine a human-friendly document label: prefer particulars, then pr_no, then consolidated pr_no
            $doc_label = '';
            $pqr = $this->db->query("SELECT particulars, pr_no FROM project_list WHERE id = " . intval($project_id) . " LIMIT 1");
            if ($pqr && $pqr->num_rows > 0) {
                $prow = $pqr->fetch_assoc();
                $doc_label = trim((string)($prow['particulars'] ?? ''));
                if ($doc_label === '') {
                    $doc_label = trim((string)($prow['pr_no'] ?? ''));
                }
            }
            if ($doc_label === '') {
                $cres = $this->db->query("SELECT pr_no FROM consolidated WHERE project_id = " . intval($project_id) . " AND pr_no <> '' LIMIT 1");
                if ($cres && $cres->num_rows > 0) {
                    $doc_label = trim((string)$cres->fetch_assoc()['pr_no']);
                }
            }
            if ($doc_label === '') $doc_label = 'Document #' . intval($project_id);

            $message = $this->db->real_escape_string("{$actor} has a comment in {$doc_label}");

            // ensure notifications table exists (safe)
            $this->ensureNotificationsTableExists();
            // notify users, but ignore failures
            $dt_n = date('Y-m-d H:i:s');
            try{
                $users = $this->db->query("SELECT id FROM users");
                if ($users) {
                    while ($u = $users->fetch_assoc()) {
                        $rid = intval($u['id']);
                        if ($rid === intval($uid)) continue;
                        @$this->db->query("INSERT INTO notifications (user_id, actor_id, project_id, message, is_read, created_at) VALUES ({$rid}, {$uid}, " . intval($project_id) . ", '{$message}', 0, '{$dt_n}')");
                    }
                }
            }catch(Exception $_){ }

            // notifications processed; return success for comment save
            return 1;
        }
        return 0;
    }

    function delete_comment()
    {
        extract($_POST);
        if (!isset($id) || empty($id)) return 0;
        $id = intval($id);
        // fetch comment owner
        $c = $this->db->query("SELECT user_id FROM comments WHERE id = {$id}");
        if (!$c || $c->num_rows == 0) return 0;
        $owner = $c->fetch_assoc();
        $owner_id = isset($owner['user_id']) ? intval($owner['user_id']) : 0;
        // If current user is an employee (login_type == 3), allow delete only for own comments
        if (isset($_SESSION['login_type']) && intval($_SESSION['login_type']) === 2) {
            if (!isset($_SESSION['login_id']) || intval($_SESSION['login_id']) !== $owner_id) {
                return 0; // not allowed
            }
        }
        $delete = $this->db->query("DELETE FROM comments where id = {$id}");
        if ($delete) return 1;
        return 0;
    }

    function mark_notifications_read()
    {
        if (!isset($_SESSION['login_id'])) return 0;
        $uid = intval($_SESSION['login_id']);
        $q = $this->db->query("UPDATE notifications SET is_read = 1 WHERE user_id = {$uid} AND is_read = 0");
        if ($q) return 1;
        return 0;
    }

    function get_report()
    {
        extract($_POST);
        $data = array();
        $get = $this->db->query("SELECT t.*,p.name as ticket_for FROM ticket_list t inner join pricing p on p.id = t.pricing_id where date(t.date_created) between '$date_from' and '$date_to' order by unix_timestamp(t.date_created) desc ");
        while ($row = $get->fetch_assoc()) {
            $row['date_created'] = date("M d, Y", strtotime($row['date_created']));
            $row['name'] = ucwords($row['name']);
            $row['adult_price'] = number_format($row['adult_price'], 2);
            $row['child_price'] = number_format($row['child_price'], 2);
            $row['amount'] = number_format($row['amount'], 2);
            $data[] = $row;
        }
        return json_encode($data);
    }

    /**
     * Synchronize consolidated rows from the form into the `consolidated` table.
     * This will delete existing rows for the project and re-insert submitted rows.
     * Expects form fields `pr_no[]`, `amount[]`, `particulars[]` when present.
     */
    private function syncConsolidatedRows($projectId){
        $projectId = intval($projectId);
        if($projectId <= 0) return;
        // remove existing rows for this project
        $this->db->query("DELETE FROM consolidated WHERE project_id = {$projectId}");

        $pr_nos = isset($_POST['pr_no']) && is_array($_POST['pr_no']) ? $_POST['pr_no'] : array();
        $amounts = isset($_POST['amount']) && is_array($_POST['amount']) ? $_POST['amount'] : array();
        // Per-row particulars are posted as `particular[]` (singular) to avoid
        // colliding with the top-level `particulars` field. Accept either name
        // for compatibility.
        if (isset($_POST['particular']) && is_array($_POST['particular'])) {
            $parts = $_POST['particular'];
        } elseif (isset($_POST['particulars']) && is_array($_POST['particulars'])) {
            $parts = $_POST['particulars'];
        } else {
            $parts = array();
        }

        $max = max(array(count($pr_nos), count($amounts), count($parts)));
        $rows = array();
        $grandTotal = 0.0;
        // normalize and compute grand total
        for($i = 0; $i < $max; $i++){
            $pr = isset($pr_nos[$i]) ? trim($pr_nos[$i]) : '';
            $amtRaw = isset($amounts[$i]) ? $amounts[$i] : '';
            $amtClean = is_string($amtRaw) ? str_replace(',','', trim($amtRaw)) : $amtRaw;
            $amountVal = null;
            if($amtClean !== '' && is_numeric($amtClean)){
                $amountVal = number_format((float)$amtClean, 2, '.', '');
                $grandTotal += (float)$amountVal;
            }
            $part = isset($parts[$i]) ? trim($parts[$i]) : '';
            if($pr === '' && $part === '' && $amountVal === null) continue;
            $rows[] = array('pr'=>$pr, 'amount'=>$amountVal, 'part'=>$part);
        }

        // insert with row_order and grand_total
        $now = date('Y-m-d H:i:s');
        $rowOrder = 1;
        foreach($rows as $r){
            $prEsc = $this->db->real_escape_string($r['pr']);
            $partEsc = $this->db->real_escape_string($r['part']);
            $amountSql = ($r['amount'] === null) ? 'NULL' : "'".$this->db->real_escape_string($r['amount'])."'";
            $grandSql = ($grandTotal > 0) ? "'".$this->db->real_escape_string(number_format($grandTotal,2,'.',''))."'" : 'NULL';
            // Note: consolidated table column is `particular` (singular) in the DB dump
            $sql = "INSERT INTO consolidated (project_id, pr_no, amount, particular, grand_total, row_order, created_at) VALUES ({$projectId}, '{$prEsc}', {$amountSql}, '{$partEsc}', {$grandSql}, {$rowOrder}, '{$now}')";
            $this->db->query($sql);
            $rowOrder++;
        }

        // update project_list.amount to reflect grand total
        if($grandTotal > 0){
            $gt = number_format($grandTotal, 2, '.', '');
            $this->db->query("UPDATE project_list SET amount = '{$this->db->real_escape_string($gt)}' WHERE id = {$projectId}");
        } else {
            $this->db->query("UPDATE project_list SET amount = NULL WHERE id = {$projectId}");
        }
    }
}
