<div class="row rolenav">
    <div class="col-lg-12 rolenav">
        <ul class="nav nav-tabs pull-left" style="margin-top: 10px;">
            <li<?php if (isset($restaurants)) echo ' class="active"' ?>><a href="index.php?controller=Admin&action=invoke">Restaurants</a></li>
            <li<?php if (isset($tables)) echo ' class="active"' ?>><a href="index.php?controller=AdminTables&action=invoke">Restaurant Tables</a></li>
            <li<?php if (isset($reservations)) echo ' class="active"' ?>><a href="index.php?controller=AdminReservations&action=invoke">Reservations</a></li>
            <li<?php if (isset($users)) echo ' class="active"' ?>><a href="index.php?controller=AdminUsers&action=invoke">Users</a></li>
            <li<?php if (isset($statistics)) echo ' class="active"' ?>><a href="index.php?controller=AdminStatistics&action=invoke">Statistics</a></li>
        </ul>
        <h2 class="textalign-right">Administrator Portal</h2>
    </div>
</div>