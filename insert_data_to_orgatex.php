<?php
// Koneksi ke database
require_once "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dyelot = $_POST['dyelot'];
    $redye = $_POST['redye'];
    $machine = $_POST['machine'];
    $procedureType = $_POST['procedureType'];
    $procedureNo = $_POST['procedureNo'];
    $color = $_POST['color'];
    $recipeNo = $_POST['recipeNo'];
    $orderNo = $_POST['orderNo'];
    $customer = $_POST['customer'];
    $article = $_POST['article'];
    $colorNo = $_POST['colorNo'];
    $warna = $_POST['warna'];
    $weight = $_POST['weight'];
    $blower_speed = $_POST['blower_speed'];
    $move_speed = $_POST['move_speed'];
    $nozle = !empty($_POST['nozle']) ? $_POST['nozle'] : 0;
    $length = $_POST['length'];
    $liquorRatio = $_POST['liquorRatio'];
    $liquorQuantity = $_POST['liquorQuantity'];
    $pumpSpeed = $_POST['pumpSpeed'];
    $reelSpeed = $_POST['reelSpeed'];
    $absorption = $_POST['absorption'];
    $jsonRecipe = $_POST['recipes'];
    $jsonTreatment = $_POST['treatments'];
    $currentIP = $_POST['currentIP'];

    // echo $jsonRecipe;

    // Eksekusi stored procedure
    try {
        // Persiapkan statement untuk memanggil stored procedure
        $stmt = $pdo_orgatex->prepare("EXEC testintegration_insert 
        @Dyelot = :dyelot,
        @Redye = :redye,
        @Machine = :machine,
        @TypeOfProcedure = :procedureType,
        @ProcedureNo = :procedureNo,
        @Color = :color,
        @RecipeNo = :recipeNo,
        @OrderNo = :orderNo,
        @Customer = :customer,
        @Article = :article,
        @ColorNo = :colorNo,
        @ColourDescript = :warna,
        @Weight = :weight,
        @BlowerSpeed = :blower_speed,
        @MoveSpeed = :move_speed,
        @Nozle = :nozle,
        @Length = :length,
        @LiquorRatio = :liquorRatio,
        @LiquorQuantity = :liquorQuantity,
        @PumpSpeed = :pumpSpeed,
        @ReelSpeed = :reelSpeed,
        @Absorption = :absorption,
        @jsonRecipe = :jsonRecipe,
        @jsonTreatment = :jsonTreatment,
        @IPADDRESS = :currentIP");

        // Bind parameters
        $stmt->bindParam(':dyelot', $dyelot);
        $stmt->bindParam(':redye', $redye);
        $stmt->bindParam(':machine', $machine);
        $stmt->bindParam(':procedureType', $procedureType);
        $stmt->bindParam(':procedureNo', $procedureNo);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':recipeNo', $recipeNo);
        $stmt->bindParam(':orderNo', $orderNo);
        $stmt->bindParam(':customer', $customer);
        $stmt->bindParam(':article', $article);
        $stmt->bindParam(':colorNo', $colorNo);
        $stmt->bindParam(':warna', $warna);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':blower_speed', $blower_speed);
        $stmt->bindParam(':move_speed', $move_speed);
        $stmt->bindParam(':nozle', $nozle);
        $stmt->bindParam(':length', $length);
        $stmt->bindParam(':liquorRatio', $liquorRatio);
        $stmt->bindParam(':liquorQuantity', $liquorQuantity);
        $stmt->bindParam(':pumpSpeed', $pumpSpeed);
        $stmt->bindParam(':reelSpeed', $reelSpeed);
        $stmt->bindParam(':absorption', $absorption);
        $stmt->bindParam(':jsonRecipe', $jsonRecipe);
        $stmt->bindParam(':jsonTreatment', $jsonTreatment);
        $stmt->bindParam(':currentIP', $currentIP);

        // Eksekusi stored procedure
        $stmt->execute();
        echo json_encode(['success' => true, 'data' => 'Inserted successfully']);
    } catch (PDOException $e) {
        // Tangkap dan log error
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Tangkap error lainnya
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
