<?php
// === Ambil data API ===
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'http://36.91.14.43:8090/baubau.php?kolam=3',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_HTTPHEADER => [
        'x-api-key: tAmb4kB4ubAUaZhaR',
        'Content-Type: application/json'
    ],
]);
$response = curl_exec($curl);
if ($response === false) {
    die('Curl error: ' . curl_error($curl));
}
curl_close($curl);

$data = json_decode($response, true);
if (!isset($data['data']) || !is_array($data['data'])) {
    die('Data tidak valid atau kosong');
}

// Ambil data terbaru
$latest = $data['data'][0];

$water_temp  = $latest['waterTemperature'] ?? '-';
$tds_ppm     = $latest['tds'] ?? '-';
$ph          = $latest['ph'] ?? '-';
$salinity    = $latest['salinity'] ?? '-';
$do_val      = $latest['do'] ?? '-';
$air_temp    = $latest['temperature'] ?? '-';
$air_hum     = $latest['humidity'] ?? '-';
$timestamp   = $latest['timestamp']['$date']['$numberLong'] ?? '-';

$dateFormatted = (is_numeric($timestamp)) 
    ? date('Y-m-d H:i:s', (int) round($timestamp / 1000)) 
    : '-';

// === Rekomendasi ===
$rekom_water_temp = ($water_temp !== '-' && $water_temp < 28) ? "Suhu air rendah: gunakan pemanas/kurangi air dingin."
                   : (($water_temp !== '-' && $water_temp > 32) ? "Suhu air tinggi: tambah aerasi & shading."
                   : "Suhu air ideal untuk udang.");

$rekom_tds = ($tds_ppm !== '-' && $tds_ppm > 1500) ? "TDS terlalu tinggi: ganti air bertahap."
            : (($tds_ppm !== '-' && $tds_ppm < 800) ? "TDS rendah: tambahkan mineral/air bersalinitas tinggi."
            : "TDS aman untuk udang.");

$rekom_ph = ($ph !== '-' && $ph > 8.5) ? "pH tinggi: kurangi pengapuran & tambah air baru."
           : (($ph !== '-' && $ph < 7.5) ? "pH rendah: lakukan pengapuran sesuai dosis."
           : "pH ideal untuk udang.");

$rekom_salinity = ($salinity !== '-' && $salinity > 30) ? "Salinitas tinggi: tambahkan air tawar."
                : (($salinity !== '-' && $salinity < 15) ? "Salinitas rendah: tambahkan air laut/garam."
                : "Salinitas sesuai standar udang.");

$rekom_do = ($do_val !== '-' && $do_val < 5) ? "DO rendah: tingkatkan aerasi/kurangi pakan."
          : (($do_val !== '-' && $do_val > 8) ? "DO sangat tinggi: awasi supersaturasi."
          : "Oksigen terlarut ideal untuk udang.");

$rekom_air_temp = ($air_temp !== '-' && $air_temp < 26) ? "Suhu udara rendah: awasi fluktuasi suhu air."
                : (($air_temp !== '-' && $air_temp > 35) ? "Suhu udara tinggi: gunakan shading."
                : "Suhu udara sesuai.");

$rekom_air_hum = ($air_hum !== '-' && $air_hum < 60) ? "Kelembapan rendah: perhatikan evaporasi kolam."
               : (($air_hum !== '-' && $air_hum > 80) ? "Kelembapan tinggi: awasi perubahan cuaca."
               : "Kelembapan udara normal.");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Monitoring Tambak Udang</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{font-family:'Poppins',sans-serif;background:#f0f2f5;margin:0;padding:0;}
.header{background:#001f3f;color:#fff;padding:30px 20px;text-align:center;}
.header h1{margin:0;font-size:28px;}
.container{padding:20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;}
.card{background:#fff;padding:20px;border-radius:15px;box-shadow:0 4px 12px rgba(0,0,0,.1);text-align:center;}
.card i{font-size:28px;color:#001f3f;margin-bottom:10px;}
.value{font-size:20px;font-weight:600;color:#001f3f;}
.label{font-size:14px;color:#555;}
.rekomendasi{margin-top:8px;font-size:12px;color:#007700;}
.time{grid-column:1/-1;text-align:center;margin-top:20px;color:#001f3f;}
</style>
</head>
<body>
<div class="header">
  <img src="logo.png" alt="Logo">
  <h1>Monitoring Tambak Udang Kolam 3</h1>
</div>

<div class="container">
  <div class="card">
    <i class="fa-solid fa-temperature-half"></i>
    <div class="value"><?= $water_temp ?> �C</div>
    <div class="label">Suhu Air</div>
    <div class="rekomendasi"><?= $rekom_water_temp ?></div>
  </div>
  <div class="card">
    <i class="fa-solid fa-water"></i>
    <div class="value"><?= $tds_ppm ?> ppm</div>
    <div class="label">TDS</div>
    <div class="rekomendasi"><?= $rekom_tds ?></div>
  </div>
  <div class="card">
    <i class="fa-solid fa-flask"></i>
    <div class="value"><?= $ph ?></div>
    <div class="label">pH</div>
    <div class="rekomendasi"><?= $rekom_ph ?></div>
  </div>
  <div class="card">
    <i class="fa-solid fa-droplet"></i>
    <div class="value"><?= $salinity ?> ppt</div>
    <div class="label">Salinitas</div>
    <div class="rekomendasi"><?= $rekom_salinity ?></div>
  </div>
  <div class="card">
    <i class="fa-solid fa-circle-nodes"></i>
    <div class="value"><?= $do_val ?></div>
    <div class="label">Dissolved Oxygen</div>
    <div class="rekomendasi"><?= $rekom_do ?></div>
  </div>
  <div class="card">
    <i class="fa-solid fa-temperature-high"></i>
    <div class="value"><?= $air_temp ?> �C</div>
    <div class="label">Suhu Udara</div>
    <div class="rekomendasi"><?= $rekom_air_temp ?></div>
  </div>
  <div class="card">
    <i class="fa-solid fa-wind"></i>
    <div class="value"><?= $air_hum ?> %</div>
    <div class="label">Kelembapan Udara</div>
    <div class="rekomendasi"><?= $rekom_air_hum ?></div>
  </div>
</div>
<div class="time">Waktu Pengukuran: <b><?= $dateFormatted ?></b></div>
</body>
</html>
