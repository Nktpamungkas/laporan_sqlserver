<?php
require_once "koneksi.php"; // Pastikan koneksi berhasil dibuat

// Memeriksa koneksi
if (!isset($con_nowprd) || $con_nowprd === null) {
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
}

if (isset($_POST['print_select_zebra'])): ?>
    <style>
        @page {
            size: 6cm 3cm;
            margin: 0.21cm;
        }

        body {
            margin: 0;
            padding: 0;
            width: 6cm;
            height: 3cm;
        }
    </style>
    <?php
    require_once "phpqrcode/qrlib.php";

    $id_generate = isset($_POST['id_barcode']) ? $_POST['id_barcode'] : [];
    if (empty($id_generate)) {
        echo ("You didn't select anything");
        exit;
    }

    $value_generate = [];
    foreach ($id_generate as $id) {
        $trimmed_id = trim($id);
        if (is_numeric($trimmed_id)) {
            $value_generate[] = "'" . intval($trimmed_id) . "'";
        } else {
            echo "Invalid ID detected: $trimmed_id";
            exit;
        }
    }

    $where_value = implode(', ', $value_generate);
    $q_pinjambuku = sqlsrv_query($con_nowprd, "SELECT * FROM nowprd.buku_pinjam WHERE id IN ($where_value)");

    if (!$q_pinjambuku) {
        var_dump(sqlsrv_errors());
        exit;
    }
    ?>
    <?php while ($row_data = sqlsrv_fetch_array($q_pinjambuku, SQLSRV_FETCH_ASSOC)): ?>
        <img src="barcode.php?text=<?= sprintf("%'.06d", $row_data['id']); ?>&print=true" width="210" height="106">
    <?php endwhile; ?>

<?php else: ?>
    <html>

    <head>
        <link href="styles_cetak.css" rel="stylesheet" type="text/css">
        <title>
            <?= isset($_POST['print_select']) ? 'Print Barcode' : (isset($_POST['arsip_select']) ? 'Arsip Data Resep' : ''); ?>
        </title>
        <style>
            td {
                border: none;
            }
        </style>
    </head>

    <body onload="print();">
        <?php if (isset($_POST['print_select'])): ?>
            <?php
            $id_generate = isset($_POST['id_barcode']) ? $_POST['id_barcode'] : [];
            if (empty($id_generate)) {
                echo ("You didn't select anything");
                exit;
            }

            $value_generate = [];
            foreach ($id_generate as $id) {
                $trimmed_id = trim($id);
                if (is_numeric($trimmed_id)) {
                    $value_generate[] = "'" . intval($trimmed_id) . "'";
                } else {
                    echo "Invalid ID detected: $trimmed_id";
                    exit;
                }
            }

            $where_value = implode(', ', $value_generate);
            $q_pinjambuku = sqlsrv_query($con_nowprd, "SELECT * FROM nowprd.buku_pinjam WHERE id IN ($where_value)");
            if (!$q_pinjambuku) {
                var_dump(sqlsrv_errors());
                echo "Query failed.";
                exit;
            }
            ?>
            <table width="100%" border="0" style="width: 7in;">
                <tbody>
                    <tr>
                        <?php while ($row_data = sqlsrv_fetch_array($q_pinjambuku, SQLSRV_FETCH_ASSOC)): ?>
                            <td align="left" valign="top" style="height: 1.6in;">
                                <table width="100%" border="0" class="table-list1" style="width: 2.3in;">
                                    <tr>
                                        <img src="barcode.php?text=<?= sprintf("%'.06d", $row_data['id']); ?>&print=true&size=60"
                                            width="160px">
                                    </tr>
                                </table>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                </tbody>
            </table>
        <?php elseif (isset($_POST['arsip_select'])): ?>
            <?php
            $id_generate = $_POST['id_barcode'] ?? [];
            if (empty($id_generate)) {
                echo ("You didn't select anything");
                exit;
            }

            $value_generate = [];
            foreach ($id_generate as $id) {
                $trimmed_id = trim($id);
                $value_generate[] = "'" . intval($trimmed_id) . "'";
            }
            $where_value = implode(', ', $value_generate);
            $q_pinjambuku = sqlsrv_query($con_nowprd, "UPDATE nowprd.buku_pinjam SET status_file = 'Arsip' WHERE id IN ($where_value)");

            if ($q_pinjambuku) {
                echo '<script language="javascript">';
                echo 'let text = "Data Resep Berhasil di arsip !";
                    if (confirm(text)) {
                        document.location.href = "prd_pinjam_stdcckwarna.php";
                    } else {
                        document.location.href = "prd_pinjam_stdcckwarna.php";
                    }';
                echo '</script>';
            }
            ?>
        <?php elseif (isset($_POST['batalkan_arsip'])): ?>
            <?php
            $id_generate = $_POST['id_barcode'] ?? [];
            if (empty($id_generate)) {
                echo ("You didn't select anything");
                exit;
            }

            $value_generate = [];
            foreach ($id_generate as $id) {
                $trimmed_id = trim($id);
                $value_generate[] = "'" . intval($trimmed_id) . "'";
            }
            $where_value = implode(', ', $value_generate);
            $q_pinjambuku = sqlsrv_query($con_nowprd, "UPDATE nowprd.buku_pinjam SET status_file = null WHERE id IN ($where_value)");

            if ($q_pinjambuku) {
                echo '<script language="javascript">';
                echo 'let text = "Arsip berhasil dibatalkan !";
                    if (confirm(text)) {
                        document.location.href = "prd_pinjam_stdcckwarna.php";
                    } else {
                        document.location.href = "prd_pinjam_stdcckwarna.php";
                    }';
                echo '</script>';
            }
            ?>
        <?php endif; ?>
    </body>

    </html>
<?php endif; ?>