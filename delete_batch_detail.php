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
            $_SESSION['message'] = 'Inputan tidak sesuai !';
            $_SESSION['success'] = 'false'; 
            header('Location: orgatex_dyelot_view.php'); 
            exit();
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
        $_SESSION['message'] = 'Batch berhasil di hapus';
        $_SESSION['success'] = 'true'; 
        header('Location: orgatex_dyelot_view.php'); 
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Database Error: ' . $e->getMessage();
        $_SESSION['success'] = 'false'; 
        header('Location: orgatex_dyelot_view.php'); 
    } catch (Exception $e) {
        $_SESSION['message'] = 'Error: ' . $e->getMessage();
        $_SESSION['success'] = 'false'; 
        header('Location: orgatex_dyelot_view.php'); 
    }
}