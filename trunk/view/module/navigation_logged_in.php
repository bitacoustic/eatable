<!-- Navigation -->
<nav class='navbar navbar-inverse navbar-fixed-top' role='navigation'>
    <div class='container' id='navbar-content'>
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class='container navbar-info' >SFSU&nbsp;Software&nbsp;Engineering&nbsp;Project,&nbsp;Fall&nbsp;2015&nbsp;&mdash;&nbsp;For&nbsp;Demonstration&nbsp;Only</div>
        <div class='navbar-infohighlight'></div>
        <div class='navbar-header'>
            <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>
                <span class='sr-only'>Toggle navigation</span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
            </button>
            <a class='navbar-brand img-responsive' href='index.php'><img class="img-responsive" src="img/eatable_logo_white_small.png"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
            <form class='navbar-form navbar-right' role='admin' id='login_form' method='post' action='index.php'>
                <ul class="nav navbar-nav navbar-right">
                    <li><a style="color: #f6f6f6;"><em><?php echo "Hello, " . $this->user->fname; ?>!</em></a></li>
                    <?php
                    if ($this->user->isAdmin()) {
                        echo "<li><a role='view_admin_portal' href='index.php?controller=Admin&action=invoke'>View admin portal</a></li>";
                    }
                    
                    if ($this->user->isOwner()) {
                        echo "<li><a role='manage_my_restaurant' href='index.php?controller=BusinessSettings&action=invoke'>Manage my restaurant</a></li>";
                    }
                    
                    if ($this->user->isHost()) {
                        echo "<li><a role='manage_my_restaurant' href='index.php?controller=Host&action=invoke' target='_blank'>Launch host interface</a></li>";
                    }
                    ?>
                    <li><a role='view_my_account' href='index.php?controller=Account&action=invoke'>View my account</a></li>
                    <li><a role='logout' href='index.php?controller=Logout&action=invoke'>Log out</a></li>
                </ul>
            </form>
        </div>
    </div>
</nav>