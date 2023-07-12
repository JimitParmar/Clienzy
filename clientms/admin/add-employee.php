<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['clientmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $empname = $_POST['empname'];
        $empphnumber = $_POST['empphnumber'];
        $emppassword = md5($_POST['emppassword']);

        $sql = "INSERT INTO tblemployee(EmployeeName, EmployeePhnumber, Password)
                VALUES (:empname, :empphnumber,:emppassword)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':empname', $empname, PDO::PARAM_STR);
        $query->bindParam(':empphnumber', $empphnumber, PDO::PARAM_STR);
        $query->bindParam(':emppassword', $emppassword, PDO::PARAM_STR);
        $query->execute();
        
        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
            $uid = $_SESSION['clientmsaid'];
            $grantSql = "GRANT SELECT, INSERT, UPDATE, DELETE ON clientmsdb.tblclient TO '$empname'@'localhost'";
            $grantQuery = $dbh->prepare($grantSql);
            $grantQuery->execute();            
            $clientSql = "SELECT tblclient.ID, tblclient.ContactName 
              FROM tblclient 
              INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
              WHERE tblassignments.EmployeeID = :empmsuid";

    echo '<script>alert("Employee has been added.")</script>';
    echo "<script>window.location.href ='add-employee.php'</script>";
} else {
    echo '<script>alert("Something went wrong. Please try again.")</script>';
}
    }
}
?>


<!DOCTYPE HTML>
<html>

<head>
    <title>Client Management System | Add Employee</title>

    <script type="application/x-javascript">
        addEventListener("load", function() {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <!-- Graph CSS -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- jQuery -->
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'>
    <!-- lined-icons -->
    <link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
    <!-- //lined-icons -->
    <script src="js/jquery-1.10.2.min.js"></script>
    <!--clock init-->
    <script src="js/css3clock.js"></script>
    <!--Easy Pie Chart-->
    <!--skycons-icons-->
    <script src="js/skycons.js"></script>
    <!--//skycons-icons-->
</head>

<body>
    <div class="page-container">
        <!--/content-inner-->
        <div class="left-content">
            <div class="inner-content">

                <?php include_once('includes/header.php'); ?>
                <!--//outer-wp-->
                <div class="outter-wp">
                    <!--/sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Home</a></li>
                            <li class="active">Add Employee</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Add Employee </h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Employee Name</label>
                                        <input type="text" name="empname" placeholder="Employee Name" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Phone Number</label>
                                        <input type="int" name="empphnumber" placeholder="Phone Number" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Password</label>
                                        <input type="password" name="emppassword" placeholder="Password" class="form-control" required='true'>
                                    </div>
                                    <button type="submit" class="btn btn-default" name="submit" id="submit">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
        <?php include_once('includes/sidebar.php'); ?>
        <div class="clearfix"></div>
    </div>
    <script>
        var toggle = true;

        $(".sidebar-icon").click(function() {
            if (toggle) {
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $("#menu span").css({
                    "position": "absolute"
                });
            } else {
                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                setTimeout(function() {
                    $("#menu span").css({
                        "position": "relative"
                    });
                }, 400);
            }

            toggle = !toggle;
        });
    </script>
    <!--js -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>

</html>
