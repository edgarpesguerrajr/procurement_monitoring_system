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
</script>
