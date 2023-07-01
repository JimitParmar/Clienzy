<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $name = $_POST['Name'];
    $password = md5($_POST['password']);
    $_SESSION['clientmsname'] = $name;
    $sql = "SELECT EmployeeID FROM tblemployee WHERE EmployeeName = :empname AND Password = :password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':empname', $name, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $_SESSION['clientmsuid'] = $result['EmployeeID'];
        $_SESSION['login'] = $name;
        echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
        exit();
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Client Management System || Login Page</title>
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
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
       <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <!--clock init-->
</head> 
<body>
    <div class="error_page">
        <div class="error-top">
            <div class="login">
                <div class="buttons login">
                    <h3 class="inner-tittle t-inner" style>Employee Login In</h3>
                </div>
                <form id="login" method="post" name="login"> 
                    <input type="text"  placeholder="Employee Name"  name = Name required="true"/>
                    <input type="password" placeholder="Password" name="password" required="true"/>
                    <div class="submit"><input type="submit" onclick="myFunction()" value="Login" name="login"></div>
                    <div class="clearfix"></div>
                    <div class="new">
                        <p><a href="forgot-password.php">Forgot Password?</a></p>
                        <p ><a href="../index.php">Back Home!!</a></p>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--//login-->
    <!--footer section start-->
    <div class="footer">
        <?php include_once('includes/footer.php'); ?>
    </div>
    <!--footer section end-->
    <!--/404-->
    <!--js -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
