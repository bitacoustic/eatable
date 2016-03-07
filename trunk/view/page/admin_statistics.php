<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>eaTable</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/modern-business.css" rel="stylesheet">
        <link href="css/eatable.css" rel="stylesheet">
    </head>

    <body>
        <!-- Navigation bar -->
        <?php include_once('view/module/navigation.php') ?>

        <!-- Page Content -->
        <div class="container">
            <?php include_once('view/module/admin_tabs.php') ?>
            <div class="row">
                <div class="col-md-12">
                    <p><a href="view/page/phpinfo.php" target="_blank">PHP Info</a></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p><a href="view/page/analytics.php" target="_blank">Google Analytics Embedded Dashboard</a> (permissions required)</p>
                    <p><a href="https://analytics.google.com/analytics/web/#report/defaultid/a70749625w108028935p112555736/" target="_blank">Google Analytics Dashboard</a> (permissions required)</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include_once('view/module/footer.php'); ?>

        <!-- jQuery Version 1.11.0 -->
        <script src="js/jquery-1.11.0.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
