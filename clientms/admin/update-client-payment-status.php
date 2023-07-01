<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['clientmsaid']) == 0) {
    header('location:logout.php');
} else {
    $adminid = $_SESSION['clientmsaid'];


    if (isset($_POST['client_id']) && isset( $_POST['payment_status'])) {
        $clientId = $_POST['client_id'];
        $paymentstatus = $_POST['payment_status'];

        // Update the payment status in the database
        $sql = "UPDATE tblclient SET PaymentStatus = :payment_status WHERE ID = :clientId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':payment_status', $paymentstatus, PDO::PARAM_STR);
        $query->bindParam(':clientId', $clientId, PDO::PARAM_INT);
  
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
