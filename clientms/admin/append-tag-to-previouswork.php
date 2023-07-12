<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['clientmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['client_id']) && isset($_POST['tag_value'])) {
        $clientId = $_POST['client_id'];
        $tagValue = $_POST['tag_value'];

        $sql = "SELECT previouswork FROM tblclient WHERE ID = :clientId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':clientId', $clientId, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $previousWork = $result['previouswork'];
            $previousWorkArray = explode(',', $previousWork);
            $trimmedTagValue = trim($tagValue);

            if (!in_array($trimmedTagValue, $previousWorkArray)) {
                $previousWorkArray[] = $trimmedTagValue;
                $previousWork = implode(', ', $previousWorkArray);

                $updateSql = "UPDATE tblclient SET previouswork = :previousWork WHERE ID = :clientId";
                $updateQuery = $dbh->prepare($updateSql);
                $updateQuery->bindParam(':previousWork', $previousWork, PDO::PARAM_STR);
                $updateQuery->bindParam(':clientId', $clientId, PDO::PARAM_INT);
                $updateQuery->execute();

                echo json_encode(['success' => true, 'message' => 'Tag appended to Previous Work.']);
                exit();
            }
        }
    }

    echo json_encode(['success' => false, 'message' => 'Failed to append Tag to Previous Work.']);
    exit();
}
?>
