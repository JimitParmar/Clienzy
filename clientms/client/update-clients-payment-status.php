<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['clientmsuid']) == 0) {
    header('location:logout.php');
} else {
    $employeeId = $_SESSION['clientmsuid'];


    if (isset($_POST['client_id'], $_POST['payment_status'])) {
        $clientID = $_POST['client_id'];
        $paymentStatus = $_POST['payment_status'];

        // Update the payment status in the database
        $sql = "UPDATE tblclient SET PaymentStatus = :payment_status WHERE ID = :client_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':payment_status', $paymentStatus, PDO::PARAM_STR);
        $query->bindParam(':client_id', $clientID, PDO::PARAM_INT);
  
        if ($query->execute()) {
            // Status updated successfully
            echo json_encode(['success' => true, 'message' => 'Payment status updated successfully']);
            exit();
        } else {
            // Error occurred while updating status
            echo json_encode(['success' => false, 'message' => 'Failed to update payment status']);
            exit();
        }
    } else {
        // Invalid request
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit();
    }
}
?>
