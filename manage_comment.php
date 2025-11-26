<?php
include 'db_connect.php';
session_start();
?>
<div class="container-fluid">
    <div id="msg"></div>
    <form id="manage-comment">
        <input type="hidden" name="project_id" value="<?php echo isset($_GET['project_id']) ? $_GET['project_id'] : '' ?>">
        <div class="form-group">
            <label for="comment" class="control-label">Comment</label>
            <textarea name="comment" id="comment" cols="30" rows="5" class="form-control" required></textarea>
        </div>
    </form>
</div>

<script>
    $('#manage-comment').submit(function (e) {
        e.preventDefault();
        // Use parent loader (modal content runs inside the page) to show global loading overlay
        if (typeof parent.start_load === 'function') parent.start_load();
        $.ajax({
            url: 'ajax.php?action=save_comment',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: function (resp) {
                if (resp == 1) {
                    if (typeof parent.end_load === 'function') parent.end_load();
                    parent.alert_toast('Comment saved.', 'success');
                    parent.load_comments();
                    parent.$('#uni_modal').modal('hide');
                } else {
                    $('#msg').html('<div class="alert alert-danger">An error occurred.</div>');
                    if (typeof parent.end_load === 'function') parent.end_load();
                }
            },
            error: function () {
                $('#msg').html('<div class="alert alert-danger">An error occurred.</div>');
                if (typeof parent.end_load === 'function') parent.end_load();
            }
        })
    })
</script>
