<?php
// Koneksi ke database
require_once "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validasi = $_POST['validasi'];
    $dyelot = $_POST['dyelot'];
    $batchRefNo = $_POST['dyelotRefNo'];
    $redye = $_POST['reDye'];
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // Eksekusi stored procedure
    try {

        if($validasi != "DeleteBatchAssistant/$batchRefNo"){
            echo json_encode(['success' => false, 'message' => 'Tidak sama yang anda input dengan yang seharusnya']);
        }

        // Persiapkan statement untuk memanggil stored procedure
        $stmt = $pdo_orgatex->prepare("EXEC delete_batch_detail
        @IPADDRESS = :currentIP,
        @RedyeDelete  = :reDye,
        @DyelotDelete  = :dyelot,
        @BatchRefNoDelete  = :batchRefNo");

        // Bind parameters
        $stmt->bindParam(':currentIP', $dyelot);
        $stmt->bindParam(':reDye', $redye);
        $stmt->bindParam(':dyelot', $dyelot);
        $stmt->bindParam(':batchRefNo', $batchRefNo);

        // Eksekusi stored procedure
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Batch berhasil di hapus']);
    } catch (PDOException $e) {
        // Tangkap dan log error
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Tangkap error lainnya
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}