
<?php
include "../koneksi.php";

// Pertama, coba ambil nilai dari $_GET
$prodOrder = isset($_GET['prod_order']) ? $_GET['prod_order'] : '';
$line = isset($_GET['line']) ? $_GET['line'] : '';

// Jika nilai prod_order atau line kosong, coba ambil dari $_POST
if (empty($prodOrder)) {
    $prodOrder = isset($_POST['prod_order']) ? $_POST['prod_order'] : '';
}

if (empty($line)) {
    $line = isset($_POST['line']) ? $_POST['line'] : '';
}

// Pastikan nilai prod_order dan line tidak kosong
if (empty($prodOrder) || empty($line)) {
    echo json_encode([
        'success' => false,
        'message' => 'Production Order atau Reservation Line tidak boleh kosong.'
    ]);
    exit;
}
$dtMain = [];
$queryMain = "SELECT DISTINCT
                    TRIM(VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE) AS DYELOT,
                    VIEWPRODUCTIONRESERVATION.GROUPLINE AS REDYE,
                    '1409' AS MACHINE,
                    0 AS TYPEOFPROCEDURE,
                    0 AS PROCEDURENO,
                    0 AS COLOR,
                    TRIM(VIEWPRODUCTIONRESERVATION.SUBCODE01) || '-' || TRIM(VIEWPRODUCTIONRESERVATION.SUFFIXCODE) AS RECIPENO,
                    '' AS ARTICLE,
                    TRIM(i.SUBCODE05) AS COLORNO,
                    SUBSTR(TRIM(i.WARNA), 1, 20) AS WARNA,
                    ir.INITIALUSERPRIMARYQUANTITY AS WEIGHT,
                    0 AS LENGTH,
                    VIEWPRODUCTIONRESERVATION.PICKUPQUANTITY AS LIQUORATIO,
                    CAST((CAST(ir.INITIALUSERPRIMARYQUANTITY * VIEWPRODUCTIONRESERVATION.PICKUPQUANTITY AS DECIMAL(18, 2))) - ir.INITIALUSERPRIMARYQUANTITY * (a.VALUEDECIMAL / 100) AS DECIMAL(18, 2)) AS LIQUORQUANTITY,
                    0 AS PUMPSPEED,
                    0 AS REELSPEED,
                    0 AS ABSORPTION
                FROM
                    VIEWPRODUCTIONRESERVATION
                LEFT JOIN ITXVIEWKK i ON i.PRODUCTIONORDERCODE = VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE
                LEFT JOIN ITXVIEW_RESERVATION ir ON ir.PRODRESERVATIONLINKGROUPCODE = VIEWPRODUCTIONRESERVATION.PRODRESERVATIONLINKGROUPCODE
                    AND ir.PRODUCTIONORDERCODE = VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE
                LEFT JOIN RECIPE r ON r.SUBCODE01 = VIEWPRODUCTIONRESERVATION.SUBCODE01
                    AND r.SUFFIXCODE = VIEWPRODUCTIONRESERVATION.SUFFIXCODE
                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = r.ABSUNIQUEID
                    AND a.NAMENAME = 'CarryOver'
                WHERE
                    VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = '$prodOrder'
                    AND VIEWPRODUCTIONRESERVATION.GROUPLINE = '$line'";
                    $stmtMain = db2_exec($conn1,$queryMain);
                    $dataMain = db2_fetch_assoc($stmtMain);
if (!$dataMain) {
    echo json_encode([
        'success' => false,
        'message' => 'No data found for the given production order and line.'
    ]);
    exit;
}else{
    $sqlDetail = "SELECT
                        ITXVIEWRESEP.RECIPENUMBERID,
                        ITXVIEWRESEP.SUFFIXCODE,
                        ITXVIEWRESEP.SUBCODE01,
                        ITXVIEWRESEP.SUBCODE02,
                        ITXVIEWRESEP.SUBCODE03,
                        COALESCE(ITXVIEWRESEP2.GROUPNUMBER, ITXVIEWRESEP.GROUPNUMBER) AS GROUPNUMBER,
                        CASE
                            WHEN ITXVIEWRESEP.CODE = '' THEN COALESCE(ITXVIEWRESEP2.CODE, ITXVIEWRESEP.SUBCODE01_RESERVATION)
                            ELSE ITXVIEWRESEP.CODE
                        END	AS CODE,
                        CASE
                            WHEN ITXVIEWRESEP.SUBCODE = '' THEN ITXVIEWRESEP2.SUBCODE
                            ELSE ITXVIEWRESEP.SUBCODE
                        END	AS SUBCODE,
                        CASE
                            WHEN ITXVIEWRESEP.COMMENTLINE IS NULL OR ITXVIEWRESEP.COMMENTLINE = '' THEN ITXVIEWRESEP2.COMMENTLINE
                            ELSE ITXVIEWRESEP.COMMENTLINE
                        END	AS COMMENTLINE,
                        CASE
                            WHEN ITXVIEWRESEP.LONGDESCRIPTION IS NULL OR ITXVIEWRESEP.LONGDESCRIPTION = '' THEN ITXVIEWRESEP2.LONGDESCRIPTION
                            ELSE ITXVIEWRESEP.LONGDESCRIPTION
                        END	AS LONGDESCRIPTION,
                        CASE
                            WHEN ITXVIEWRESEP.CONSUMPTION IS NULL OR ITXVIEWRESEP.CONSUMPTION = 0 THEN ITXVIEWRESEP2.CONSUMPTION
                            ELSE ITXVIEWRESEP.CONSUMPTION
                        END	AS CONSUMPTION,
                        CASE
                            WHEN 
                                CASE
                                    WHEN ITXVIEWRESEP.CONSUMPTIONTYPE IS NULL OR ITXVIEWRESEP.CONSUMPTIONTYPE = '' THEN ITXVIEWRESEP2.CONSUMPTIONTYPE
                                    ELSE ITXVIEWRESEP.CONSUMPTIONTYPE
                                END = '1' THEN 'g/l'
                            WHEN
                                CASE
                                    WHEN ITXVIEWRESEP.CONSUMPTIONTYPE IS NULL OR ITXVIEWRESEP.CONSUMPTIONTYPE = '' THEN ITXVIEWRESEP2.CONSUMPTIONTYPE
                                    ELSE ITXVIEWRESEP.CONSUMPTIONTYPE
                                END = '2' THEN '%'
                        END	AS CONSUMPTIONTYPE,
                        CASE
                            WHEN ITXVIEWRESEP.RECIPETYPE = '1' THEN CAST( ((ITXVIEWRESEP.PICKUPPERCENTAGE/100 * $dataMain[WEIGHT]) + ITXVIEWRESEP.RESIDUALBATHVOLUME) * ITXVIEWRESEP2.CONSUMPTION AS DECIMAL(18, 7))
                            ELSE 
                                CASE
                                    WHEN ITXVIEWRESEP2.CONSUMPTIONTYPE = '1' THEN CAST( (($dataMain[WEIGHT] * VIEWPRODUCTIONRESERVATION.PICKUPQUANTITY) * ITXVIEWRESEP2.CONSUMPTION) / 1000 AS DECIMAL(18, 7))
                                    WHEN ITXVIEWRESEP2.CONSUMPTIONTYPE = '2' THEN CAST( (($dataMain[WEIGHT] * (ITXVIEWRESEP2.CONSUMPTION/100)) * 1000) / 1000 AS DECIMAL(18, 7))
                                END
                        END AS QTY,
                        CASE 
                            WHEN ITXVIEWRESEP2.CONSUMPTIONTYPE = '1' THEN 'kg'
                            WHEN ITXVIEWRESEP2.CONSUMPTIONTYPE = '2' THEN 'kg'
                        END AS UOM 
                    FROM
                        VIEWPRODUCTIONRESERVATION VIEWPRODUCTIONRESERVATION
                    LEFT JOIN ITXVIEWRESEP ITXVIEWRESEP ON VIEWPRODUCTIONRESERVATION.SUFFIXCODE = ITXVIEWRESEP.SUFFIXCODE_RESERVATION
                        AND VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = ITXVIEWRESEP.PRODUCTIONORDERCODE
                        AND VIEWPRODUCTIONRESERVATION.SUBCODE01 = ITXVIEWRESEP.SUBCODE01_RESERVATION
                        AND VIEWPRODUCTIONRESERVATION.COMPANYCODE = ITXVIEWRESEP.COMPANYCODE
                    LEFT JOIN ITXVIEWRESEP2 ITXVIEWRESEP2 ON ITXVIEWRESEP2.RECIPESUBCODE01 = ITXVIEWRESEP.CODE AND ITXVIEWRESEP2.RECIPESUFFIXCODE = ITXVIEWRESEP.SUFFIXCODE
                    WHERE 
                        VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = '$prodOrder'
                        AND VIEWPRODUCTIONRESERVATION.GROUPLINE = '$line'
                    ORDER BY
                        VIEWPRODUCTIONRESERVATION.GROUPLINE,
                        ITXVIEWRESEP.RECIPENUMBERID,
                        ITXVIEWRESEP.GROUPNUMBER,
                        ITXVIEWRESEP.SEQUENCE,
                        ITXVIEWRESEP2.GROUPNUMBER,
                        ITXVIEWRESEP2.SEQUENCE ASC";

        $resultDetail = db2_exec($conn1, $sqlDetail);
        $recipes = [];
        $dtMain = $dataMain;
        $detail = [];
        while ($recipe = db2_fetch_assoc($resultDetail)) {
            // Retrieve quantity
            $sqlQty = "SELECT
                        RECIPE.RECIPETYPE,
                        RECIPE.PICKUPPERCENTAGE,
                        RECIPE.RESIDUALBATHVOLUME,
                        ITXVIEWRESEP1.RECIPENUMBERID,
                        ITXVIEWRESEP1.GROUPNUMBER,
                        ITXVIEWRESEP1.CONSUMPTION,
                        ITXVIEWRESEP1.CONSUMPTIONTYPE,
                        ITXVIEWRESEP1.SUBCODE01,
                        ITXVIEWRESEP1.SUBCODE02,
                        ITXVIEWRESEP1.SUBCODE03,	
                        CASE
                            WHEN RECIPE.RECIPETYPE = '1' THEN 
                                CAST( (CAST(RECIPE.PICKUPPERCENTAGE AS DECIMAL(18,2))/100 * $dataMain[WEIGHT] + CAST(RECIPE.RESIDUALBATHVOLUME AS DECIMAL(18,2)) * ITXVIEWRESEP1.CONSUMPTION) / 1000 AS DECIMAL(18, 7))
                            ELSE 
                                CASE
                                    WHEN TRIM(ITXVIEWRESEP1.CONSUMPTIONTYPE) = '1' THEN CAST( $dataMain[WEIGHT] * $dataMain[LIQUORATIO] * CAST(ITXVIEWRESEP1.CONSUMPTION AS DECIMAL(18,4)) / 1000 AS DECIMAL(18, 7))
                                    WHEN TRIM(ITXVIEWRESEP1.CONSUMPTIONTYPE) = '2' THEN CAST( (($dataMain[WEIGHT] * (CAST(ITXVIEWRESEP1.CONSUMPTION AS DECIMAL(18,4)) / 100)) * 1000 )/ 1000 AS DECIMAL(18, 7))
                                END
                        END AS QTY,
                        CASE 
                            WHEN ITXVIEWRESEP1.CONSUMPTIONTYPE = '1' THEN 'kg'
                            WHEN ITXVIEWRESEP1.CONSUMPTIONTYPE = '2' THEN 'kg'
                        END AS UOM		           
                        FROM
                            RECIPE RECIPE
                        LEFT JOIN ITXVIEWRESEP1 ITXVIEWRESEP1 ON RECIPE.NUMBERID = ITXVIEWRESEP1.RECIPENUMBERID
                        WHERE 
                            ITXVIEWRESEP1.RECIPENUMBERID = '$recipe[RECIPENUMBERID]'
                            AND ITXVIEWRESEP1.SUBCODE01 = '$recipe[SUBCODE01]'
                            AND ITXVIEWRESEP1.SUBCODE02 = '$recipe[SUBCODE02]'
                            AND ITXVIEWRESEP1.SUBCODE03 = '$recipe[SUBCODE03]'
                            AND ITXVIEWRESEP1.GROUPNUMBER = '$recipe[GROUPNUMBER]'";

            $resultQty = db2_exec($conn1, $sqlQty);

            $quantityData = db2_fetch_assoc($resultQty);

            // Add quantity to the recipe data
            $recipe['QUANTITY'] = $recipe['QTY'] ? $recipe['QTY'] :  $quantityData['QTY']; // Default to 0 if no quantity found
            $recipe['CONSUMPTIONTYPEQTY'] = $recipe['UOM'] ? $recipe['UOM']  : $quantityData['UOM']; // Default to 0 if no quantity found
            $recipes[] = $recipe;
        }
        // Kirimkan data sebagai JSON
        echo json_encode([
            'success' => true,
            'dataheader' => [
                'recipeno' => $dataMain['RECIPENO'],
                'color' => $dataMain['COLORNO'],
                'warna' => $dataMain['WARNA'],
                'dyelot' => $dataMain['DYELOT']
            ],
            'item' => $dtMain,
            'recipes'=> $recipes,
            'detail' => $detail

        ]);
    }
?>


