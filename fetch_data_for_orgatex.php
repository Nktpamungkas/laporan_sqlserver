<?php
require_once "koneksi.php";

if (isset($_POST['production_number'])) {
    $orderCode = $_POST['production_number'];
    $groupLineArray = $_POST['groupLineArray'];
    $groupLine = $_POST['groupLine'];
    $arrayDariGetGroup = $_POST['arrayDariGetGroup'];
    
    // Query to fetch the main data based on production number
    $sqlMain = "SELECT
                    DISTINCT 
                    TRIM(VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE) AS DYELOT,
                    VIEWPRODUCTIONRESERVATION.GROUPLINE AS REDYE,
                    '1409' AS MACHINE,
                    0 AS TYPEOFPROCEDURE,
                    0 AS PROCEDURENO,
                    0 AS COLOR,
                    TRIM(VIEWPRODUCTIONRESERVATION.SUBCODE01) || '-' || TRIM(VIEWPRODUCTIONRESERVATION.SUFFIXCODE) AS RECIPENO,
                    i.PROJECTCODE AS ORDERNO,
                    i.ORDPRNCUSTOMERSUPPLIERCODE AS CUSTOMER,
                    '' AS ARTICLE,
                    TRIM(i.SUBCODE05) AS COLORNO,
                    SUBSTR(TRIM(i.WARNA),1,20) AS WARNA,
                    ir.INITIALUSERPRIMARYQUANTITY AS WEIGHT,
                    0 AS LENGTH,
                    VIEWPRODUCTIONRESERVATION.PICKUPQUANTITY AS LIQUORATIO,
                    CAST((CAST(ir.INITIALUSERPRIMARYQUANTITY * VIEWPRODUCTIONRESERVATION.PICKUPQUANTITY AS DECIMAL(18, 2))) - ir.INITIALUSERPRIMARYQUANTITY * (a.VALUEDECIMAL/100) AS DECIMAL(18,2)) AS LIQUORQUANTITY,
                    0 AS PUMPSPEED,
                    0 AS REELSPEED,
                    0 AS ABSORPTION
                FROM
                    VIEWPRODUCTIONRESERVATION
                LEFT JOIN ITXVIEWKK i ON i.PRODUCTIONORDERCODE = VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE
                LEFT JOIN ITXVIEW_RESERVATION ir ON ir.PRODRESERVATIONLINKGROUPCODE = VIEWPRODUCTIONRESERVATION.PRODRESERVATIONLINKGROUPCODE 
                                                AND ir.PRODUCTIONORDERCODE = VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE
                LEFT JOIN RECIPE r ON r.SUBCODE01 = VIEWPRODUCTIONRESERVATION.SUBCODE01 AND r.SUFFIXCODE = VIEWPRODUCTIONRESERVATION.SUFFIXCODE 
                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = r.ABSUNIQUEID AND a.NAMENAME = 'CarryOver'
                WHERE
                    VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = '$orderCode'
                    AND VIEWPRODUCTIONRESERVATION.GROUPLINE = '$groupLine'";

    $resultMain = db2_exec($conn1, $sqlMain);
    $dataMain = db2_fetch_assoc($resultMain);

    if ($dataMain) {
        // Prepare the recipes data
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
                        VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = '$orderCode'
                        AND VIEWPRODUCTIONRESERVATION.GROUPLINE IN ($groupLineArray) 
                        -- AND NOT 
                        --     CASE
                        --         WHEN 
                        --             CASE
                        --                 WHEN ITXVIEWRESEP.CONSUMPTIONTYPE IS NULL OR ITXVIEWRESEP.CONSUMPTIONTYPE = '' THEN ITXVIEWRESEP2.CONSUMPTIONTYPE
                        --                 ELSE ITXVIEWRESEP.CONSUMPTIONTYPE
                        --             END = '1' THEN 'g/l'
                        --         WHEN
                        --             CASE
                        --                 WHEN ITXVIEWRESEP.CONSUMPTIONTYPE IS NULL OR ITXVIEWRESEP.CONSUMPTIONTYPE = '' THEN ITXVIEWRESEP2.CONSUMPTIONTYPE
                        --                 ELSE ITXVIEWRESEP.CONSUMPTIONTYPE
                        --             END = '2' THEN '%'
                        --     END IS NULL 
                    ORDER BY
                        VIEWPRODUCTIONRESERVATION.GROUPLINE,
                        ITXVIEWRESEP.RECIPENUMBERID,
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

        $sqlTreatment = "SELECT 
                            DISTINCT 
                            * 
                            FROM (SELECT
                                    VIEWPRODUCTIONRESERVATION.GROUPLINE,
                                    ITXVIEWRESEP.RECIPENUMBERID, 
                                    ITXVIEWRESEP.GROUPNUMBER, 
                                    CASE
                                        WHEN CAST( LENGTH(ITXVIEWRESEP.SUBCODE01) AS VARCHAR(5)) = '1' THEN ITXVIEWRESEP.SUBCODE01_RESERVATION
                                        ELSE ITXVIEWRESEP.SUBCODE01
                                    END AS SUBCODE01,
                                    CASE
                                        WHEN CAST( LENGTH(ITXVIEWRESEP.SUBCODE01) AS VARCHAR(5)) = '1' THEN ITXVIEWRESEP.SUFFIXCODE_RESERVATION
                                        ELSE ITXVIEWRESEP.SUFFIXCODE
                                    END AS SUFFIXCODE
                                FROM
                                    VIEWPRODUCTIONRESERVATION
                                LEFT JOIN ITXVIEWRESEP ON VIEWPRODUCTIONRESERVATION.SUFFIXCODE = ITXVIEWRESEP.SUFFIXCODE_RESERVATION
                                    AND VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = ITXVIEWRESEP.PRODUCTIONORDERCODE
                                    AND VIEWPRODUCTIONRESERVATION.SUBCODE01 = ITXVIEWRESEP.SUBCODE01_RESERVATION
                                    AND VIEWPRODUCTIONRESERVATION.COMPANYCODE = ITXVIEWRESEP.COMPANYCODE
                                LEFT JOIN ITXVIEWRESEP2 ITXVIEWRESEP2 ON ITXVIEWRESEP2.RECIPESUBCODE01 = ITXVIEWRESEP.CODE AND ITXVIEWRESEP2.RECIPESUFFIXCODE = ITXVIEWRESEP.SUFFIXCODE
                                WHERE 
                                    VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = '$orderCode'
                                    AND VIEWPRODUCTIONRESERVATION.GROUPLINE IN ($groupLineArray) 
                                    -- AND NOT 
                                    --     CASE
                                    --         WHEN 
                                    --             CASE
                                    --                 WHEN ITXVIEWRESEP.CONSUMPTIONTYPE IS NULL OR ITXVIEWRESEP.CONSUMPTIONTYPE = '' THEN ITXVIEWRESEP2.CONSUMPTIONTYPE
                                    --                 ELSE ITXVIEWRESEP.CONSUMPTIONTYPE
                                    --             END = '1' THEN 'g/l'
                                    --         WHEN
                                    --             CASE
                                    --                 WHEN ITXVIEWRESEP.CONSUMPTIONTYPE IS NULL OR ITXVIEWRESEP.CONSUMPTIONTYPE = '' THEN ITXVIEWRESEP2.CONSUMPTIONTYPE
                                    --                 ELSE ITXVIEWRESEP.CONSUMPTIONTYPE
                                    --             END = '2' THEN '%'
                                    --     END IS NULL 
                                GROUP BY
                                    VIEWPRODUCTIONRESERVATION.GROUPLINE,
                                    ITXVIEWRESEP.RECIPENUMBERID,
                                    ITXVIEWRESEP.SUBCODE01,
                                    ITXVIEWRESEP.SUFFIXCODE,
                                    ITXVIEWRESEP.GROUPNUMBER,
                                    ITXVIEWRESEP.SUBCODE01_RESERVATION,
                                    ITXVIEWRESEP.SUFFIXCODE_RESERVATION 
                                ORDER BY
                                    VIEWPRODUCTIONRESERVATION.GROUPLINE,
                                    ITXVIEWRESEP.RECIPENUMBERID,
                                    ITXVIEWRESEP.GROUPNUMBER)";

        $resultTreatment = db2_exec($conn1, $sqlTreatment);
        $treatments = [];

        // SCHEDULE DYEING
        // $sqlScheduleDye  = "SELECT * FROM tbl_schedule WHERE (no_resep = '$orderCode-$groupLine' OR no_resep = '$orderCode-$groupLine') OR (no_resep2 = '$orderCode-$groupLine' OR no_resep2 = '$orderCode-$groupLine')";
        // $sqlScheduleDye  = "SELECT * FROM tbl_schedule WHERE nokk = '$orderCode' ORDER BY id DESC LIMIT 1";
        $sqlScheduleDye  = "SELECT
                                a.nokk,
                                a.langganan,
                                a.no_hanger,
                                b.no_mesin_lama AS no_mesin,
                                a.no_mesin AS no_mesin_new
                            FROM
                                tbl_schedule a
                            LEFT JOIN tbl_mesin b ON b.no_mesin = a.no_mesin
                            WHERE
                                a.nokk = '$orderCode' 
                            ORDER BY
                                a.id DESC 
                            LIMIT 1";
        $resultScheduleDye = mysqli_query($con_db_dyeing, $sqlScheduleDye);
        $dataSchedule = mysqli_fetch_assoc($resultScheduleDye);

        while ($treatment = db2_fetch_assoc($resultTreatment)) {
            $sqlTreatmentDetail = "SELECT
                                        CAST(a.VALUEDECIMAL AS DECIMAL(4)) AS MAINPROGRAM
                                    FROM
                                        RECIPE r 
                                    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = r.ABSUNIQUEID AND a.FIELDNAME IN ('CoolingProgram1','CoolingProgram')
                                    WHERE
                                        r.SUBCODE01 = '{$treatment['SUBCODE01']}' AND r.SUFFIXCODE = '{$treatment['SUFFIXCODE']}'
                                        AND NOT a.VALUEDECIMAL IS NULL
                                    UNION ALL 
                                    SELECT
                                        CAST(a2.VALUEDECIMAL AS DECIMAL(4)) AS MAINPROGRAM
                                    FROM
                                        RECIPE r 
                                    LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = r.ABSUNIQUEID AND a2.FIELDNAME IN ('MainProgram1','MainProgram')
                                    WHERE
                                        r.SUBCODE01 = '{$treatment['SUBCODE01']}' AND r.SUFFIXCODE = '{$treatment['SUFFIXCODE']}'
                                        AND NOT a2.VALUEDECIMAL IS NULL";

            $resultArray = db2_exec($conn1, $sqlTreatmentDetail);
            while ($treatmentArray = db2_fetch_assoc($resultArray)) {
                $sqlDescTreatment = $pdo_orgatex->prepare("SELECT TOP 1 * FROM dbo.Treatments WHERE TreatmentNo = $treatmentArray[MAINPROGRAM]");
                $sqlDescTreatment->execute();
                $mainDesc = $sqlDescTreatment->fetch(PDO::FETCH_ASSOC);

                if($treatmentArray['MAINPROGRAM']){
                    $sqlValidationTreatment = $pdo_orgatex->prepare("SELECT
                                                                        Machines.MachineNo,
                                                                        Machines.MachineName,
                                                                        Machines.MGroupNo,
                                                                        Treatment_MGroups.TreatmentNo,
                                                                        Treatments.TreatmentName
                                                                    FROM
                                                                        dbo.Machines Machines
                                                                    LEFT JOIN dbo.Treatment_MGroups Treatment_MGroups ON Treatment_MGroups.MGroupNo = Machines.MGroupNo
                                                                    LEFT JOIN dbo.Treatments Treatments ON Treatments.TreatmentNo = Treatment_MGroups.TreatmentNo
                                                                    WHERE
                                                                        Machines.MachineNo = '$dataSchedule[no_mesin]'
                                                                        AND Treatment_MGroups.TreatmentNo = '$treatmentArray[MAINPROGRAM]'");
                    $sqlValidationTreatment->execute();
                    $ValidationTreatment = $sqlValidationTreatment->fetch(PDO::FETCH_ASSOC);
                    if($ValidationTreatment['TreatmentNo']){
                        $mainValidation         = '<span class="pcoded-micon"><i class="fa fa-check-circle" style="color: #0bdf0f;"></i></span> Available';
                        $mainValidationNumber   = 1;
                    }else{
                        $sqlValidationTreatment_main = $pdo_orgatex_main->prepare("SELECT
                                                                                        Machine.machine_no AS MachineNo,
                                                                                        Machine.name AS MachineName,
                                                                                        Machine.group_no AS MGroupNo,
                                                                                        Treat2TreatGroup.Treatment_ID AS TreatmentNo,
                                                                                        Treatments.TreatmentName
                                                                                    FROM
                                                                                        Machine
                                                                                    LEFT JOIN Treat2TreatGroup on Treat2TreatGroup.TreaGroup_ID = Machine.group_no
                                                                                    LEFT JOIN [ORGATEX-INTEG].dbo.Treatments Treatments ON Treatments.TreatmentNo = Treat2TreatGroup.Treatment_ID
                                                                                    WHERE
                                                                                        Machine.machine_no = '$dataSchedule[no_mesin]'
                                                                                        AND Treat2TreatGroup.Treatment_ID = '$treatmentArray[MAINPROGRAM]'");
                        $sqlValidationTreatment_main->execute();
                        $ValidationTreatment_main = $sqlValidationTreatment_main->fetch(PDO::FETCH_ASSOC);
                        if($ValidationTreatment_main['TreatmentNo']){
                            $mainValidation         = '<span class="pcoded-micon"><i class="fa fa-check-circle" style="color: #0bdf0f;"></i></span> Available';
                            $mainValidationNumber   = 1;
                        }else{
                            $mainValidation     = '<span class="pcoded-micon"><i class="fa fa-exclamation-circle" style="color: #ff1b00;"></i></span> Not Available | Please add your treatment to the machines.';
                            $mainValidationNumber   = 0;
                        }
                    }
                }else{
                    $mainValidation     = '<span class="pcoded-micon"><i class="fa fa-exclamation-circle" style="color: #ff1b00;"></i></span> Not Available | Please add your treatment to the machines.';
                    $mainValidationNumber   = 2;
                }

                $treatments[] = [
                    'SUBCODE01' => $treatment['SUBCODE01'],
                    'SUFFIXCODE' => $treatment['SUFFIXCODE'],
                    'MAINPROGRAM' => $treatmentArray['MAINPROGRAM'], // No Treatment
                    'TREATMENTNAME' => $mainDesc['TreatmentName'], // Desc Treatment
                    'VALIDATION' =>  $mainValidation, // Validasi jika ada treatmentnya
                    'VALIDATIONNUMBER' =>  $mainValidationNumber // Validasi jika ada treatmentnya
                ];
            }
        }

        $sqlColor = "SELECT
                        u.CODE,
                        a.VALUEDECIMAL AS RED,
                        a2.VALUEDECIMAL AS GREEN,
                        a3.VALUEDECIMAL AS BLUE
                    FROM
                        USERGENERICGROUP u 
                    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = u.ABSUNIQUEID AND a.FIELDNAME = 'RGBvalueR'
                    LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = u.ABSUNIQUEID AND a2.FIELDNAME = 'RGBvalueG'
                    LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = u.ABSUNIQUEID AND a3.FIELDNAME = 'RGBvalueB'
                    WHERE
                        u.USERGENERICGROUPTYPECODE = 'CL1'
                        AND u.CODE = '$dataMain[COLORNO]'";
    
        $resultColor = db2_exec($conn1, $sqlColor);
        $dataColor = db2_fetch_assoc($resultColor);

        $formulaColor = 256*256*$dataColor['BLUE'] + 256*$dataColor['GREEN'] + $dataColor['RED'];

        echo json_encode([
            'success' => true,
            'dyelot' => $dataMain['DYELOT'],
            'redye' => $dataMain['REDYE'],
            'machine' => $dataSchedule['no_mesin'],
            'machine_new' => $dataSchedule['no_mesin_new'],
            'type_of_procedure' => $dataMain['TYPEOFPROCEDURE'],
            'procedure_no' => $dataMain['PROCEDURENO'],
            'color' => $formulaColor,
            'recipe_number' => $dataMain['RECIPENO'],
            'order_number' => $dataMain['ORDERNO'],
            'customer_name' => $dataSchedule['langganan'],
            'article' => $dataSchedule['no_hanger'],
            'color_number' => $dataMain['COLORNO'],
            'warna' => $dataMain['WARNA'],
            'weight' => $dataMain['WEIGHT'],
            'length' => $dataMain['LENGTH'],
            'liquorRatio' => $dataMain['LIQUORATIO'],
            'liquorQuantity' => $dataMain['LIQUORQUANTITY'],
            'pumpSpeed' => $dataMain['PUMPSPEED'],
            'reelSpeed' => $dataMain['REELSPEED'],
            'absorption' => $dataMain['ABSORPTION'],
            'group_line' => (!empty($groupLineArray) || count($groupLineArray) > 1) ? $groupLineArray  :  $groupLine,
            'arrayDariGetGroup' => $arrayDariGetGroup,
            'recipes' => $recipes,
            'treatments' => $treatments
        ]);
    } else {
        // echo json_encode(['success' => false]);
        echo json_encode(['success' => false, 'message' => 'Data sudah ada dalam daftar Batch Export.']);
    }
}
