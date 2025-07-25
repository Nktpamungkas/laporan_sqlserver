<?php 
    require "koneksi.php"; 
    $number_id = $_GET['number'] ?? '';
    $query = "SELECT
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABDIPCLRF' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABDIPCLRF,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABAAW' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABBLEEDW,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABSALIVA' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABSALIVA,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCOLOUR' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCOLOUR,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRNUM' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRNUM,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCAT' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCAT,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABBLEED' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABBLEED,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABDYEMIG' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABDYEMIG,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABPHEN' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABPHEN,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABTRANS' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABTRANS,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABPLIGHT' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABPLIGHT,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCHLOR' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCHLOR,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABDIPCLRW' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABDIPCLRW,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABDIPCLRP' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABDIPCLRP,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWH' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWH,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWR' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWR,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRPR' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRPR,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWHN' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWHN,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWRN' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWRN,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRPRN' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRPRN,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWHP' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWHP,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWRP' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWRP,        
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRPRP' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRPRP,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCRKDRY' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCRKDRY,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCRKWET' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCRKWET,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABLIGHT' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABLIGHT,    
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABNCHLOR' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABNCHLOR,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCSSTN' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCSSTN,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWHA' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWHA,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWHC' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWHC,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWHW' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWHW,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWRA' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWRA,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWRC' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWRC,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRSWRW' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRSWRW,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRPRT' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRPRT,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRPRC' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRPRC,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABCLRPRW' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABCLRPRW,
                MAX(CASE WHEN q2.CHARACTERISTICCODE = 'LABBLEEDR' THEN 
                    CASE 
                        WHEN q2.VALUEQUANTITY = 2.5 THEN '2-3'
                        WHEN q2.VALUEQUANTITY = 3.5 THEN '3-4'
                        WHEN q2.VALUEQUANTITY = 4.5 THEN '4-5'
                        WHEN q2.VALUEQUANTITY IS NULL THEN '0'
                        ELSE LEFT(CAST(q2.VALUEQUANTITY AS VARCHAR), 1)
                    END
                ELSE '0' END) AS QTY_LABBLEEDR
            FROM
                QUALITYDOCUMENT q
            LEFT JOIN ADSTORAGE a ON
                a.UNIQUEID = q.ABSUNIQUEID
                AND a.FIELDNAME = 'labdipnumber'
            LEFT JOIN QUALITYDOCLINE q2 ON
                q2.QUALITYDOCUMENTHEADERCODE = q.HEADERCODE
                AND q2.QUALITYDOCUMENTHEADERNUMBERID = q.HEADERNUMBERID
                AND q2.QUALITYDOCUMENTLOTCODE = q.LOTCODE
            -- LEFT JOIN QUALITYCHARACTERISTICTYPE q3 ON
            --     q3.CODE = q2.CHARACTERISTICCODE
            WHERE
                q2.QUALITYDOCUMENTHEADERCODE = 'LABDIP' AND 
                a.VALUESTRING LIKE '%$number_id%'
            GROUP BY
                q.HEADERCODE, 
                q.HEADERNUMBERID, 
                q.LOTCODE";
    $stmt = db2_exec($conn1, $query);
    $row = db2_fetch_assoc($stmt);
?>
<h3 style="text-align:center; margin-bottom:10px;">QUALITY STANDARD</h3>
<table border="1" style="border-collapse: collapse; width: 100%; text-align: center;">
    <thead>
        <tr>
            <th rowspan="2">COLOR FASTNESS</th>
            <th rowspan="2">COLOR CHANGE</th>
            <th colspan="6">COLOR STAINING</th>
        </tr>
        <tr>
            <th>ACETATE</th>
            <th>COTTON</th>
            <th>NYLON</th>
            <th>POLY</th>
            <th>ACRYLIC</th>
            <th>WOOL</th>
        </tr>
    </thead>
    <tbody>
        <!-- TO WASH -->
        <tr>
            <td>TO WASH</td>
            <td><?= $row['QTY_LABDIPCLRF'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWHA'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWH'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWHN'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWHP'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWHC'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWHW'] ?? 'N/A' ?></td>
        </tr>

        <!-- TO WATER -->
        <tr>
            <td>TO WATER</td>
            <td><?= $row['QTY_LABDIPCLRW'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWRA'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWR'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWRN'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWRP'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWRC'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRSWRW'] ?? 'N/A' ?></td>
        </tr>

        <!-- TO PERSPIRATION -->
        <tr>
            <td>TO PERSPIRATION</td>
            <td><?= $row['QTY_LABDIPCLRP'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRPRT'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRPR'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRPRN'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRPRP'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRPRC'] ?? 'N/A' ?></td>
            <td><?= $row['QTY_LABCLRPRW'] ?? 'N/A' ?></td>
        </tr>

        <!-- TO CROCKING -->
        <tr>
            <td rowspan="2">TO CROCKING</td>
            <td>DRY</td>
            <td colspan="6"><?= $row['QTY_LABCRKDRY'] ?? 'N/A' ?></td>
        </tr>
        <tr>
            <td>WET</td>
            <td colspan="6"><?= $row['QTY_LABCRKWET'] ?? 'N/A' ?></td>
        </tr>

        <!-- TO LIGHT -->
        <tr>
            <td>TO LIGHT</td>
            <td colspan="7"><?= $row['QTY_LABLIGHT'] ?? 'N/A' ?></td>
        </tr>
        <tr>
            <td>PERSPIRATION LIGHT</td>
            <td colspan="7"><?= $row['QTY_LABPLIGHT'] ?? 'N/A' ?></td>
        </tr>
        <tr>
            <td>BLEEDING ROOT</td>
            <td colspan="7"><?= $row['QTY_LABBLEEDR'] ?? 'N/A' ?></td>
        </tr>
        <tr>
            <td>BLEEDING WATERMARK</td>
            <td colspan="7"><?= $row['QTY_LABBLEEDW'] ?? 'N/A' ?></td>
        </tr>
        <tr>
            <td>DYE MIGRATION</td>
            <td colspan="7"><?= $row['QTY_LABDYEMIG'] ?? 'N/A' ?></td>
        </tr>
        <tr>
            <td>PHENOLIC YELLOWING</td>
            <td colspan="7"><?= $row['QTY_LABPHEN'] ?? 'N/A' ?></td>
        </tr>
        <tr>
            <td>CHLORINE</td>
            <td colspan="7"><?= $row['QTY_LABCHLOR'] ?? 'N/A' ?></td>
        </tr>
        <tr>
            <td>NON-CHLORINE</td>
            <td colspan="7"><?= $row['QTY_LABNCHLOR'] ?? 'N/A' ?></td>
        </tr>
        <tr>
            <td>CROSS STAINING</td>
            <td colspan="7"><?= $row['QTY_LABCSSTN'] ?? 'N/A' ?></td>
        </tr>
        <tr>
            <td>APPEARANCE AFTER WASH</td>
            <td colspan="7"><?= $row['QTY_LABAAW'] ?? 'N/A' ?></td>
        </tr>
        <tr>
            <td>SALIVA</td>
            <td colspan="7"><?= $row['QTY_LABSALIVA'] ?? 'N/A' ?></td>
        </tr>
    </tbody>
</table>
