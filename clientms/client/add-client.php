<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['clientmsuid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $inputDate1 = $_POST['expiry1'];
        // Convert input date to a DateTime object
        $dateTime = new DateTime($inputDate1);
        // Add 2 years to the input date
        $expiryDate1 = $dateTime->add(new DateInterval('P2Y'))->format('Y-m-d');

        $inputDate2 = $_POST['expiry2'];
		$expiryDate2 = null; // Initialize expiryDate3 as null

		if (!empty($inputDate2)) {
			// Convert input date to a DateTime object
			$dateTime = new DateTime($inputDate2);
			// Add 2 years to the input date
			$expiryDate2 = $dateTime->add(new DateInterval('P2Y'))->format('Y-m-d');
		}


        $inputDate3 = $_POST['expiry3'];
		$expiryDate3 = null; // Initialize expiryDate3 as null

		if (!empty($inputDate3)) {
			// Convert input date to a DateTime object
			$dateTime = new DateTime($inputDate3);
			// Add 2 years to the input date
			$expiryDate3 = $dateTime->add(new DateInterval('P2Y'))->format('Y-m-d');
		}

        $clientmsaid = $_SESSION['clientmsuid'];
        $cname = $_POST['cname'];
        $comname = $_POST['comname'];
        $address = $_POST['address'];
        $cellphnumber = $_POST['cellphnumber'];
        $ophnumber = $_POST['ophnumber'];
        $email = $_POST['email'];
        $notes = $_POST['notes'];

        // Retrieve the employee name who added the client
        $employeeID = $_SESSION['clientmsuid'];
        $sql = "SELECT EmployeeName FROM tblemployee WHERE EmployeeID = :employeeID";
        $query = $dbh->prepare($sql);
        $query->bindParam(':employeeID', $employeeID, PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $employeeName = $row['EmployeeName'];

        // Insert the client with the employee name
        $sql = "INSERT INTO tblclient (ContactName, CompanyName, Address, Cellphnumber, Otherphnumber, Email, Notes, ClientAddedBy,expiryDate1, expiryDate2, expiryDate3)
        VALUES (:cname, :comname, :address, :cellphnumber, :ophnumber, :email, :notes, :employeeName, :expiryDate1, :expiryDate2, :expiryDate3)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':cname', $cname, PDO::PARAM_STR);
        $query->bindParam(':comname', $comname, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':cellphnumber', $cellphnumber, PDO::PARAM_STR);
        $query->bindParam(':ophnumber', $ophnumber, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':notes', $notes, PDO::PARAM_STR);
        $query->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
        if (!empty($inputDate1)) {
			$query->bindParam(':expiryDate1', $expiryDate1, PDO::PARAM_STR);
		} else {
			$query->bindValue(':expiryDate1', null, PDO::PARAM_NULL);
		}
        if (!empty($inputDate2)) {
			$query->bindParam(':expiryDate2', $expiryDate2, PDO::PARAM_STR);
		} else {
			$query->bindValue(':expiryDate2', null, PDO::PARAM_NULL);
		}
        if (!empty($inputDate3)) {
			$query->bindParam(':expiryDate3', $expiryDate3, PDO::PARAM_STR);
		} else {
			$query->bindValue(':expiryDate3', null, PDO::PARAM_NULL);
		}
        $query->execute();

        $lastInsertedClientId = $dbh->lastInsertId();
        if ($lastInsertedClientId > 0) {
            echo '<script>alert("Client has been added.")</script>';
            echo "<script>window.location.href ='add-client.php'</script>";
        } else {
            echo '<script>alert("Something went wrong. Please try again.")</script>';
        }
    }
}
?>


<!DOCTYPE HTML>
<html>

<head>
    <title>Client Management System || Add Clients</title>

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
            <div class="inner-content">                               <!-- header-starts -->
<?php include_once('includes/header.php');?>
                <!--//outer-wp-->
                <div class="outter-wp">
                    <!--/sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Home</a></li>
                            <li class="active">Add Clients</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle1" style="margin-left: 32px;">Add Clients </h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Contact Name</label>
                                        <input type="text" name="cname" placeholder="Contact Name" value="" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Company Name</label>
                                        <input type="text" name="comname" placeholder="Company Name" value="" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Address</label>
                                        <textarea type="text" name="address" placeholder="Address" value="" class="form-control" required='true' rows="4" cols="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Cell Phone Number</label>
                                        <input type="text" name="cellphnumber" value="" placeholder="Cell Phone Number" class="form-control" maxlength='10' pattern="[0-9]+">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Other Phone Number</label>
                                        <input type="text" name="ophnumber" value="" placeholder="Work Phone Number" class="form-control" maxlength='10' pattern="[0-9]+">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email Address</label>
                                        <input type="email" name="email" value="" placeholder="Email address" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Notes</label>
                                        <textarea type="text" name="notes" placeholder="Notes" value="" class="form-control" required='true' rows="4" cols="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                    </div>
                                    <div class="form-group">
                                        <label for="creation_date">Creation Date(IceGate):</label>
                                        <input type="date" class="form-control" id="expiry_date" name="expiry1">
                                    </div>
                                    <div class="form-group">
                                        <label for="expiry_date">Creation Date(DGFT)</label>
                                        <input type="date" class="form-control" id="expiry_date" name="expiry2">
                                    </div>
                                    <div class="form-group">
                                        <label for="expiry_date">Creation Date(Class3)</label>
                                        <input type="date" class="form-control" id="expiry_date" name="expiry3">
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
<?php ?>
