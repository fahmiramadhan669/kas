<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    die();
}
require '../koneksi.php';
$tgl_awal = $_POST['tanggal_awal'];
$tgl_akhir = $_POST['tanggal_akhir'];

$format_tgl_awal = date('d-m-Y', strtotime($tgl_awal));
$format_tgl_akhir = date('d-m-Y', strtotime($tgl_akhir));

?>

<html>
    <head>
        <title>Kas | Laporan</title>
        <style>
             th {
            background-color: grey;
            color: white;
            }
            .total {
                margin-left: 12.5%;
                margin-top: 10px;
            }
            @media print {
                #cetak {
                    display: none;
                }   
            }
            .table-laporan {
                margin-top: 65px;
            }
            
        </style>
    </head>
    <body>
        <center>
            <div class="table-laporan">
                <?= "<h3>Laporan Kas dari tanggal " . $format_tgl_awal . " s.d ". $format_tgl_akhir . "</h3>" ?>
                
                <table border="1" style="width: 75%;">
                    <tr>
                        <th width="1%">No</th>
                        <th>Penanggung Jawab</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                        <th>Kas Masuk</th>
                        <th>Jenis Kas</th>
                        <th>Kas Keluar</th>
                    </tr>
                    <?php
                    $no = 1;
                    $sql_query = mysqli_query($koneksi, "SELECT * from kas WHERE tgl BETWEEN '$tgl_awal' AND '$tgl_akhir'");
                    while ($data = mysqli_fetch_array($sql_query)) {
                        $tgl = $data['tgl'];
                        $format_tgl = date('d-m-Y', strtotime($tgl));
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data['penanggung']; ?></td>
                        <td><?php echo $data['keterangan']; ?></td>
                        <td align="center"><?php echo $format_tgl; ?></td>
                        <td align="center"><?php echo number_format($data['jumlah']).",-"; ?></td>
                        <td align="center"><?php echo $data['jenis']; ?></td>
                        <td align="center"><?php echo number_format($data['keluar']).",-"; ?></td>
                    </tr>
                    <?php
                        ini_set("display_errors","Off");
                        $total = $total+$data['jumlah'];
                        $total_keluar = $total_keluar+$data['keluar'];
                        $saldo_akhir = $total - $total_keluar;                      
                        } 
                    ?>
                </table>
            </center>
            <table class="total">
                <tr>
                    <td>Total Kas Masuk</td>
                    <td>:</td>
                    <td><?php echo " Rp." . number_format($total).",-"; ?></td>        
                </tr>
                <tr>
                    <td>Total Kas Keluar</td>
                    <td>:</td>
                    <td><?php echo " Rp." . number_format($total_keluar).",-"; ?></td>        
                </tr>
                <tr>
                    <td>Jumlah Saldo</td>
                    <td>:</td>
                    <td><?php echo " Rp." . number_format($saldo_akhir).",-"; ?></td>        
                </tr>
            </table>
        </div>
        <center> <button onClick="window.print()" id="cetak">Cetak Laporan</button> </center>

    </body>
</html>