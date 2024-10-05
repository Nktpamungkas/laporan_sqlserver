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
    $weight = $_POST['weight'];
    $length = $_POST['length'];
    $liquorRatio = $_POST['liquorRatio'];
    $liquorQuantity = $_POST['liquorQuantity'];
    $pumpSpeed = $_POST['pumpSpeed'];
    $reelSpeed = $_POST['reelSpeed'];
    $absorption = $_POST['absorption'];
    $jsonRecipe = $_POST['recipes'];

    echo $jsonRecipe;

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
        @Weight = :weight,
        @Length = :length,
        @LiquorRatio = :liquorRatio,
        @LiquorQuantity = :liquorQuantity,
        @PumpSpeed = :pumpSpeed,
        @ReelSpeed = :reelSpeed,
        @Absorption = :absorption,
        @jsonRecipe = :jsonRecipe");

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
    $stmt->bindParam(':weight', $weight);
    $stmt->bindParam(':length', $length);
    $stmt->bindParam(':liquorRatio', $liquorRatio);
    $stmt->bindParam(':liquorQuantity', $liquorQuantity);
    $stmt->bindParam(':pumpSpeed', $pumpSpeed);
    $stmt->bindParam(':reelSpeed', $reelSpeed);
    $stmt->bindParam(':absorption', $absorption);
    $stmt->bindParam(':jsonRecipe', $jsonRecipe);

    // Eksekusi stored procedure
    // if ($stmt->execute()) {
    //     echo json_encode(['success' => true, 'data' => 'Inserted successfully']);
    // } else {
    //     echo json_encode(['success' => true, 'data' => $stmt->errorInfo()]);
    // }
}
