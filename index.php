<?php
include "connection.php";

$sql = mysqli_query($connect, "SELECT * FROM `data`");
?>
<table border="1">
    <tr>
        <th>ID</th>
        <th>QRCODE</th>
    </tr>
<?php
while ($data = mysqli_fetch_array($sql)) {
    $id = $data["id"];
    $logo = "./logo/".$data["logo"];
    $qrcode_name = "./qrcode/".$id.".png";

    // Code for QRCode 
    $data = isset($_GET['data']) ? $_GET['data'] : $id;
    $size = isset($_GET['size']) ? $_GET['size'] : '200x200';
    $logo = isset($_GET['logo']) ? $_GET['logo'] : $logo;

    // header('Content-type: image/png');
    // Get QR Code image from Google Chart API
    // http://code.google.com/apis/chart/infographics/docs/qr_codes.html
    $QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs='.$size.'&chl='.urlencode($data));
    if($logo !== FALSE){
        $logo = imagecreatefromstring(file_get_contents($logo));

        $QR_width = imagesx($QR);
        $QR_height = imagesy($QR);
        
        $logo_width = imagesx($logo);
        $logo_height = imagesy($logo);
        
        // Scale logo to fit in the QR Code
        $logo_qr_width = $QR_width/3;
        $scale = $logo_width/$logo_qr_width;
        $logo_qr_height = $logo_height/$scale;
        
        imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
    }
    imagepng($QR, $qrcode_name);
    imagedestroy($QR);

    echo "<tr><td>$id</td><td><img src='$qrcode_name'></td></tr>";
}
?>    
</table>