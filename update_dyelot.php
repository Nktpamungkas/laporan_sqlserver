<?php
require_once "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dyelotToDelete = $_POST['DyelotToDelete'];
    $redyeToDelete = $_POST['RedyeToDelete'];
    $importState = $_POST['ImportState'];

    try {
        $stmt = $pdo_orgatex->prepare("EXEC dbo.testintegration_update :DyelotToDelete, :RedyeToDelete, :ImportState");
        $stmt->bindParam(':DyelotToDelete', $dyelotToDelete);
        $stmt->bindParam(':RedyeToDelete', $redyeToDelete);
        $stmt->bindParam(':ImportState', $importState);

        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Tangkap error lainnya
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
