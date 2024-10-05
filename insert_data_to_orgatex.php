<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collecting data from the AJAX request
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

    // Create JSON for recipes
    $jsonRecipe = json_encode($_POST['recipes']);

    // Prepare the SQL statement for testintegration_insert
    $stmt = $conn->prepare("EXEC testintegration_insert 
        @Dyelot = ?, 
        @Redye = ?, 
        @Machine = ?, 
        @TypeOfProcedure = ?, 
        @ProcedureNo = ?, 
        @Color = ?, 
        @RecipeNo = ?, 
        @OrderNo = ?, 
        @Customer = ?, 
        @Article = ?, 
        @ColorNo = ?, 
        @Weight = ?, 
        @Length = ?, 
        @LiquorRatio = ?, 
        @LiquorQuantity = ?, 
        @PumpSpeed = ?, 
        @ReelSpeed = ?, 
        @Absorption = ?, 
        @jsonRecipe = ?");

    // Bind parameters
    $stmt->bind_param(
        "siissisiisiiddds",
        $dyelot,
        $redye,
        $machine,
        $procedureType,
        $procedureNo,
        $color,
        $recipeNo,
        $orderNo,
        $customer,
        $article,
        $colorNo,
        $weight,
        $length,
        $liquorRatio,
        $liquorQuantity,
        $pumpSpeed,
        $reelSpeed,
        $absorption,
        $jsonRecipe
    );

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
