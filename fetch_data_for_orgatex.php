<?php
require_once "koneksi.php";

if (isset($_POST['production_number'])) {
    $productionNumber = $_POST['production_number'];

    // Split the production number into order code and group line
    list($orderCode, $groupLine) = explode('-', $productionNumber);

    // Query to fetch the main data based on production number
    $sqlMain = "SELECT
                    DISTINCT 
                    VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE AS DYELOT,
                    VIEWPRODUCTIONRESERVATION.GROUPLINE AS REDYE,
                    '1410' AS MACHINE,
                    0 AS TYPEOFPROCEDURE,
                    0 AS PROCEDURENO,
                    0 AS COLOR,
                    TRIM(VIEWPRODUCTIONRESERVATION.SUBCODE01) || '-' || TRIM(VIEWPRODUCTIONRESERVATION.SUFFIXCODE) AS RECIPENO,
                    i.PROJECTCODE AS ORDERNO,
                    i.ORDPRNCUSTOMERSUPPLIERCODE AS CUSTOMER,
                    '' AS ARTICLE,
                    SUBSTR(TRIM(VIEWPRODUCTIONRESERVATION.SUBCODE01), 7, 7) AS COLORNO,
                    ir.INITIALUSERPRIMARYQUANTITY AS WEIGHT,
                    0 AS LENGTH,
                    VIEWPRODUCTIONRESERVATION.PICKUPQUANTITY AS LIQUORATIO,
                    0 AS LIQUORQUANTITY,
                    0 AS PUMPSPEED,
                    0 AS REELSPEED,
                    0 AS ABSORPTION
                FROM
                    VIEWPRODUCTIONRESERVATION
                LEFT JOIN ITXVIEWKK i ON i.PRODUCTIONORDERCODE = VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE
                LEFT JOIN ITXVIEW_RESERVATION ir ON ir.PRODRESERVATIONLINKGROUPCODE = VIEWPRODUCTIONRESERVATION.PRODRESERVATIONLINKGROUPCODE 
                                                AND ir.PRODUCTIONORDERCODE = VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE 
                WHERE
                    VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = '$orderCode'
                    AND VIEWPRODUCTIONRESERVATION.GROUPLINE = '$groupLine'";

    $resultMain = db2_exec($conn1, $sqlMain);
    $dataMain = db2_fetch_assoc($resultMain);

    if ($dataMain) {
        // Prepare the recipes data
        $sqlDetail = "SELECT
                ITXVIEWRESEP.RECIPENUMBERID,
                ITXVIEWRESEP.SUBCODE01,
                ITXVIEWRESEP.SUBCODE02,
                ITXVIEWRESEP.SUBCODE03,
                ITXVIEWRESEP.GROUPNUMBER,
                CASE
                    WHEN ITXVIEWRESEP.CODE = '' THEN ITXVIEWRESEP2.CODE
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
                    WHEN ITXVIEWRESEP.RECIPETYPE = '1' THEN CAST( ((ITXVIEWRESEP.PICKUPPERCENTAGE/100 * 1000) + ITXVIEWRESEP.RESIDUALBATHVOLUME) * ITXVIEWRESEP2.CONSUMPTION AS DECIMAL(18, 4))
                    ELSE 
                        CASE
                            WHEN ITXVIEWRESEP2.CONSUMPTIONTYPE = '1' THEN CAST( ((1000 * VIEWPRODUCTIONRESERVATION.PICKUPQUANTITY) * ITXVIEWRESEP2.CONSUMPTION) / 1000 AS DECIMAL(18, 4))
                            WHEN ITXVIEWRESEP2.CONSUMPTIONTYPE = '2' THEN CAST( (1000 * (ITXVIEWRESEP2.CONSUMPTION/100)) * 1000 AS DECIMAL(18, 4))
                        END
                END AS QTY,
                CASE 
                    WHEN ITXVIEWRESEP2.CONSUMPTIONTYPE = '1' THEN 'Kg'
                    WHEN ITXVIEWRESEP2.CONSUMPTIONTYPE = '2' THEN 'g'
                END AS UOM 
            FROM
                VIEWPRODUCTIONRESERVATION VIEWPRODUCTIONRESERVATION
            LEFT JOIN ITXVIEWRESEP ITXVIEWRESEP ON VIEWPRODUCTIONRESERVATION.SUFFIXCODE = ITXVIEWRESEP.SUFFIXCODE_RESERVATION
                AND VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = ITXVIEWRESEP.PRODUCTIONORDERCODE
                AND VIEWPRODUCTIONRESERVATION.SUBCODE01 = ITXVIEWRESEP.SUBCODE01_RESERVATION
                AND VIEWPRODUCTIONRESERVATION.COMPANYCODE = ITXVIEWRESEP.COMPANYCODE
            LEFT JOIN ITXVIEWRESEP2 ITXVIEWRESEP2 ON ITXVIEWRESEP2.RECIPESUBCODE01 = ITXVIEWRESEP.CODE AND ITXVIEWRESEP2.RECIPESUFFIXCODE = ITXVIEWRESEP.SUFFIXCODE
            WHERE 
                VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = '$orderCode'
                AND VIEWPRODUCTIONRESERVATION.GROUPLINE = '$groupLine'
            ORDER BY
                ITXVIEWRESEP.GROUPNUMBER,
                ITXVIEWRESEP.SEQUENCE,
                ITXVIEWRESEP2.GROUPNUMBER,
                ITXVIEWRESEP2.SEQUENCE ASC";

        $resultDetail = db2_exec($conn1, $sqlDetail);
        $recipes = [];

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
                            CAST( (CAST(RECIPE.PICKUPPERCENTAGE AS DECIMAL(18,2))/100 * $dataMain[WEIGHT] + CAST(RECIPE.RESIDUALBATHVOLUME AS DECIMAL(18,2)) * ITXVIEWRESEP1.CONSUMPTION) / 1000 AS DECIMAL(18, 4))
                        ELSE 
                            CASE
                                WHEN TRIM(ITXVIEWRESEP1.CONSUMPTIONTYPE) = '1' THEN CAST( $dataMain[WEIGHT] * $dataMain[LIQUORATIO] * CAST(ITXVIEWRESEP1.CONSUMPTION AS DECIMAL(18,4)) / 1000 AS DECIMAL(18, 4))
                                WHEN TRIM(ITXVIEWRESEP1.CONSUMPTIONTYPE) = '2' THEN CAST( ($dataMain[WEIGHT] * (CAST(ITXVIEWRESEP1.CONSUMPTION AS DECIMAL(18,4)) / 100)) * 1000 AS DECIMAL(18, 4))
                            END
                    END AS QTY,
                    CASE 
                        WHEN ITXVIEWRESEP1.CONSUMPTIONTYPE = '1' THEN 'Kg'
                        WHEN ITXVIEWRESEP1.CONSUMPTIONTYPE = '2' THEN 'g'
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

        echo json_encode([
            'success' => true,
            'dyelot' => $dataMain['DYELOT'],
            'redye' => $dataMain['REDYE'],
            'machine' => $dataMain['MACHINE'],
            'type_of_procedure' => $dataMain['TYPEOFPROCEDURE'],
            'procedure_no' => $dataMain['PROCEDURENO'],
            'color' => $dataMain['COLOR'],
            'recipe_number' => $dataMain['RECIPENO'],
            'order_number' => $dataMain['ORDERNO'],
            'customer_name' => $dataMain['CUSTOMER'],
            'article' => $dataMain['ARTICLE'],
            'color_number' => $dataMain['COLORNO'],
            'weight' => $dataMain['WEIGHT'],
            'length' => $dataMain['LENGTH'],
            'liquorRatio' => $dataMain['LIQUORATIO'],
            'liquorQuantity' => $dataMain['LIQUORQUANTITY'],
            'pumpSpeed' => $dataMain['PUMPSPEED'],
            'reelSpeed' => $dataMain['REELSPEED'],
            'absorption' => $dataMain['ABSORPTION'],
            'recipes' => $recipes
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
}
