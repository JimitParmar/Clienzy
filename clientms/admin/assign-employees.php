<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['clientmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $clientId = intval($_GET['addid']);
        $employeeIds = $_POST['employee_ids'];

        foreach ($employeeIds as $employeeId) {
            $sql = "INSERT INTO tblassignments (ID, EmployeeID) VALUES (:clientId, :employeeId)";

            $query = $dbh->prepare($sql);
            $query->bindParam(':clientId', $clientId, PDO::PARAM_INT);
            $query->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
            $query->execute();
        }

        echo '<script>alert("Employees assigned successfully.")</script>';
        echo "<script>window.location.href ='manage-client.php'</script>";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Client Management System || Assign Employees</title>
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
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet'
          type='text/css'>
    <!-- lined-icons -->
    <link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
    <!-- /js -->
    <script src="js/jquery-1.10.2.min.js"></script>
    <!-- //js-->
</head>
<body>
    <div class="page-container">
        <!--/content-inner-->
        <div class="left-content">
            <div class="inner-content">
                <!-- header-starts -->
                <?php include_once('includes/header.php'); ?>
                <!-- //header-ends -->
                <!--outter-wp-->
                <div class="outter-wp">
                    <!--sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Home</a></li>
                            <li class="active">Assign Employees</li>
                        </ol>
                    </div>
                    <!--//sub-heard-part-->
                    <div class="graph-visual tables-main">


                        <h3 class="inner-tittle two">Assign Employees</h3>
                        <div class="graph">
                            <div class="tables">
                                <form method="post">
                                    <table class="table" border="1">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Employee Name</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM tblemployee";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $employees = $query->fetchAll(PDO::FETCH_OBJ);

                                            $cnt = 1;
                                            if ($query->rowCount() > 0) {
                                                foreach ($employees as $employee) {
                                            ?>
                                                    <tr class="active">
                                                        <th scope="row"><?php echo htmlentities($cnt); ?></th>
                                                        <td><?php echo htmlentities($employee->EmployeeName); ?></td>
                                                        <td><?php echo htmlentities($employee->EmployeePhnumber); ?></td>
                                                        <td><input type="checkbox" name="employee_ids[]"
                                                                   value="<?php echo $employee->EmployeeID; ?>"></td>
                                                    </tr>
                                            <?php
                                                    $cnt = $cnt + 1;
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td colspan="4" align="center">
                                                    <button type="submit" name="submit"
                                                            class="btn btn-default">Submit
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>

                        </div>

                    </div>
                    <!--//graph-visual-->
                </div>
                <!--//outer-wp-->
            </div>
        </div>
        <!--//content-inner-->
        <!--/sidebar-menu-->
        <?php include_once('includes/sidebar.php'); ?>
        <div class="clearfix"></div>
    </div>
    <script>
        var toggle = true;

        $(".sidebar-icon").click(function() {
            if (toggle) {
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $("#menu span").css({"position": "absolute"});
            } else {
                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                setTimeout(function() {
                    $("#menu span").css({"position": "relative"});
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
