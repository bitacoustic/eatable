<div class="row rolenav">
    <div class="col-lg-12 rolenav">
        <ul class="nav nav-tabs pull-left" style="margin-top: 10px;">        
        <li<?php if (isset($currentPage) && strcmp($currentPage, "settings") == 0) echo ' class="active"' ?>><a href="index.php?controller=BusinessSettings&action=invoke"><i class="glyphicon glyphicon-wrench"></i>&nbsp;&nbsp;Edit Settings</a></li>
        <li<?php if (isset($currentPage) && strcmp($currentPage, "menu") == 0) echo ' class="active"' ?>><a href="index.php?controller=BusinessMenu&action=invoke"><i class="glyphicon glyphicon-cutlery"></i>&nbsp;&nbsp;Edit Menu</a></li>
        <li<?php if (isset($currentPage) && strcmp($currentPage, "hosts") == 0) echo ' class="active"' ?>><a href="index.php?controller=BusinessHosts&action=invoke"><i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Manage Host Accounts</a></li>
        <li<?php if (isset($currentPage) && strcmp($currentPage, "reservations") == 0) echo ' class="active"' ?>><a href="index.php?controller=Business&action=invoke"><i class="glyphicon glyphicon-list"></i>&nbsp;&nbsp;Manage Reservations</a></li>
    </ul>
    <h2 class="textalign-right"><?php echo $this->restaurant->name; ?></h2>
    </div>
</div>