<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-primary navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <?php if (isset($_SESSION['login_id'])): ?>
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="" role="button"><i class="fas fa-bars"></i></a>
            </li>
        <?php endif; ?>
        <li>
            <a class="nav-link text-white" href="./" role="button"> <large><b><?php echo $_SESSION['system']['name'] ?></b></large></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <!-- Notifications -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" id="notif-toggle" aria-expanded="false" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="badge badge-danger navbar-badge" id="notif-count" style="display:none">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notif-menu">
                <span class="dropdown-item dropdown-header">Notifications</span>
                <div class="dropdown-divider"></div>
                <div id="notif-items" style="max-height:240px;overflow:auto">
                    <a class="dropdown-item text-center text-muted"><div class="badge badge-danger">Soon</div></a>
                </div>
            </div>
        </li>
        <!-- Dark mode toggle -->
        <li class="nav-item">
            <a class="nav-link" href="javascript:void(0)" id="dark-mode-toggle" role="button" title="Toggle dark mode">
                <i class="fas fa-moon" id="dark-mode-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
                <span>
                    <div class="d-felx badge-pill">
                        <span class="fa fa-user mr-2"></span>
                        <span><b><?php echo ucwords($_SESSION['login_firstname']) ?></b></span>
                        <span class="fa fa-angle-down ml-2"></span>
                    </div>
                </span>
            </a>

            <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
                <a class="dropdown-item" href="javascript:void(0)" id="manage_account"><i class="fa fa-cog"></i> Manage Account</a>
                <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<script>
    // Dark mode toggle logic
    (function(){
        var key = 'pms_dark_mode';
        function applyMode(enabled){
            if(enabled){
                $('body').addClass('dark-mode');
                $('#dark-mode-icon').removeClass('fa-moon').addClass('fa-sun');
            }else{
                $('body').removeClass('dark-mode');
                $('#dark-mode-icon').removeClass('fa-sun').addClass('fa-moon');
            }
        }
        // initialize
        var stored = localStorage.getItem(key);
        applyMode(stored === '1');
        // click handler
        $('#dark-mode-toggle').on('click', function(){
            var enabled = $('body').hasClass('dark-mode');
            enabled = !enabled;
            applyMode(enabled);
            try{ localStorage.setItem(key, enabled ? '1' : '0'); }catch(e){}
        });
    })();

    $('#manage_account').click(function () {
        uni_modal('Manage Account', 'manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
    })

    // Notifications loader
    (function(){
        // Small HTML-escape helper to avoid XSS and prevent undefined-function errors
        function escapeHtml(text){
            if (text === null || text === undefined) return '';
            return String(text).replace(/[&<>"'`=\/]/g, function (s) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                    '`': '&#x60;',
                    '/': '&#x2F;',
                    '=': '&#x3D;'
                })[s];
            });
        }

        function renderItems(items){
            var $c = $('#notif-items');
            $c.empty();
            if(!Array.isArray(items) || items.length === 0){
                $c.append('<a class="dropdown-item text-center text-muted">No notifications</a>');
                $('#notif-count').hide();
                return;
            }
            var unread = 0;
            items.forEach(function(it){
                if(!it.is_read) unread++;
                var actor = escapeHtml(it.actor_name || 'User');
                var pr = (it.pr_no && it.pr_no !== '') ? escapeHtml(it.pr_no) : '';
                var msg = escapeHtml(it.message || '');
                var time = escapeHtml(it.date_created || '');
                var cls = it.is_read ? '' : 'font-weight-bold';
                var text = '<a href="./index.php?page=view_document&id='+encodeURIComponent(it.project_id)+'&notif_id='+encodeURIComponent(it.id)+'" class="dropdown-item '+cls+'">'
                    + '<div class="media">'
                    + '<div class="media-body">'
                    + '<div class="small">'+msg+'</div>'
                    + '<div class="small text-muted">'+time+'</div>'
                    + '</div></div></a>';
                $c.append(text);
                $c.append('<div class="dropdown-divider"></div>');
            });
            if (unread > 0) {
                $('#notif-count').text(unread).show();
            } else {
                $('#notif-count').hide();
            }
        }

        function loadNotifications(){
            $.ajax({
                url: 'load_notifications.php',
                method: 'GET',
                dataType: 'json',
                success: function(resp){
                    console.debug('Notifications AJAX response:', resp);
                    try{ renderItems(resp); }catch(e){ console.error(e); }
                },
                error: function(){
                    $('#notif-items').html('<a class="dropdown-item text-center text-muted">Unable to load notifications</a>');
                    $('#notif-count').hide();
                }
            });
        }

        // refresh on load and every 60s
        $(function(){ loadNotifications(); setInterval(loadNotifications, 60000);
            // mark notifications read when dropdown opens
            $('#notif-toggle').closest('.dropdown').on('show.bs.dropdown', function(){
                // mark as read via AJAX and hide badge on success
                $.ajax({
                    url: 'ajax.php?action=mark_notifications_read',
                    method: 'POST',
                    success: function(resp){
                        try{ if((typeof resp === 'string' && resp.trim() == '1') || resp == 1){ $('#notif-count').hide(); loadNotifications(); } }
                        catch(e){ $('#notif-count').hide(); loadNotifications(); }
                    },
                    error: function(){ /* ignore */ }
                });
            });
        });
    })();
</script>
