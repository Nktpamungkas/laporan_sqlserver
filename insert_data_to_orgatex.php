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

    // jaga-jaga nilai numeric kosong jadi 0 agar tidak gagal convert
    $asFloat = function ($val) {
        return is_numeric($val) ? (float) $val : 0;
    };

    $weight = $asFloat($_POST['weight']);
    $blower_speed = $asFloat($_POST['blower_speed']);
    $move_speed = $asFloat($_POST['move_speed']);
    $nozle = $asFloat(!empty($_POST['nozle']) ? $_POST['nozle'] : 0);
    $length = $asFloat($_POST['length']);
    $liquorRatio = $asFloat($_POST['liquorRatio']);
    $liquorQuantity = $asFloat($_POST['liquorQuantity']);
    $pumpSpeed = $asFloat($_POST['pumpSpeed']);
    $reelSpeed = $asFloat($_POST['reelSpeed']);
    $absorption = $asFloat($_POST['absorption']);
    $jsonRecipe = $_POST['recipes'];
    $jsonTreatment = $_POST['treatments'];
    $currentIP = $_POST['currentIP'];

    // decode JSON ke array untuk pemrosesan per CODE
    $recipes = json_decode($jsonRecipe, true) ?: [];
    $treatments = json_decode($jsonTreatment, true) ?: [];

    // helper untuk sanitasi suffix dyelot
    $sanitizeSuffix = function ($val) {
        $clean = preg_replace('/[^A-Za-z0-9]/', '', (string) $val);
        return $clean;
    };

    try {
        if (empty($recipes)) {
            throw new Exception('Tidak ada data recipe yang akan diekspor.');
        }

        // kelompokkan recipe berdasarkan CallOff (atau fallback huruf urut), suffix dyelot pakai recipe.CODE
        $grouped = [];
        $fallbackIndex = 0;
        $alphabet = range('A', 'Z');

        $sanitizeFull = function ($val) {
            return preg_replace('/[^A-Za-z0-9]/', '', (string) $val);
        };

        foreach ($recipes as $rec) {
            $callOffKeyRaw = isset($rec['CallOff']) ? $rec['CallOff'] : '';
            $callOffKey = $sanitizeSuffix($callOffKeyRaw);

            if ($callOffKey === '') {
                $callOffKey = $alphabet[$fallbackIndex % count($alphabet)];
                $fallbackIndex++;
            }

            if (!isset($grouped[$callOffKey])) {
                $grouped[$callOffKey] = [
                    'recipes' => [],
                    'codeSuffix' => '',
                    'subcodes' => []
                ];
            }

            // simpan code suffix dari recipe.CODE (pertama yang valid)
            if (isset($rec['Code']) && $grouped[$callOffKey]['codeSuffix'] === '') {
                $codeSuffixClean = $sanitizeSuffix($rec['Code']);
                if ($codeSuffixClean !== '') {
                    $grouped[$callOffKey]['codeSuffix'] = $codeSuffixClean;
                }
            }

            // kumpulkan subcode untuk filter treatment
            if (isset($rec['Code'])) {
                $clean = $sanitizeSuffix($rec['Code']);
                if ($clean !== '') {
                    $grouped[$callOffKey]['subcodes'][$clean] = true;
                }
            }
            if (isset($rec['ProductCode'])) {
                $clean = $sanitizeSuffix($rec['ProductCode']);
                if ($clean !== '') {
                    $grouped[$callOffKey]['subcodes'][$clean] = true;
                }
            }

            $grouped[$callOffKey]['recipes'][] = $rec;
        }

        $resultMessages = [];

        foreach ($grouped as $callOffKey => $payload) {
            $suffix = $payload['codeSuffix'] !== '' ? $payload['codeSuffix'] : $callOffKey;
            $dyelotWithCode = $dyelot . $suffix;
            // gunakan dyelot dasar + "-" + CallOff sebagai dyelot insert
            $dyelotForInsert = $dyelot . '-' . $callOffKey;

            $treatmentList = [];
            $cnt = 1;
            $treatmentList[] = ['TreatmentCnt' => $cnt++, 'TreatmentNo' => 9990]; // Start

            // filter treatment berdasar Item yang disanitasi, cocok dengan suffix dyelot atau dyelot lengkap
            $dyelotSanitized = $sanitizeFull($dyelotForInsert);
            $suffixSanitized = $sanitizeFull($callOffKey);
            $matchedTreatments = array_values(array_filter($treatments, function ($t) use ($sanitizeFull, $dyelotSanitized, $suffixSanitized) {
                if (!isset($t['Item'])) {
                    return false;
                }
                $itemSanitized = $sanitizeFull($t['Item']);
                return $itemSanitized !== '' && ($itemSanitized === $dyelotSanitized || $itemSanitized === $suffixSanitized);
            }));

            $selectedTreatments = !empty($matchedTreatments) ? $matchedTreatments : [];

            foreach ($selectedTreatments as $t) {
                $treatmentList[] = [
                    'TreatmentCnt' => $cnt++,
                    'TreatmentNo' => $t['TreatmentNo']
                ];
            }
            $treatmentList[] = ['TreatmentCnt' => $cnt, 'TreatmentNo' => 9991]; // End

            $recipesJson = json_encode($payload['recipes']);
            $treatmentsJson = json_encode($treatmentList);

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
            $stmt->bindParam(':dyelot', $dyelotForInsert);
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
            $stmt->bindParam(':jsonRecipe', $recipesJson);
            $stmt->bindParam(':jsonTreatment', $treatmentsJson);
            $stmt->bindParam(':currentIP', $currentIP);

            // Eksekusi stored procedure
            $stmt->execute();
            $resultMessages[] = "Dyelot $dyelotWithCode sukses disimpan.";
        }

        echo json_encode(['success' => true, 'data' => $resultMessages]);
    } catch (PDOException $e) {
        // Tangkap dan log error
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Tangkap error lainnya
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
