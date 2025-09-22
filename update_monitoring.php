<?php
// === Ambil data API ===
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'http://36.91.14.43:8090/baubau.php?kolam=1',
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
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Monitoring Kualitas Air</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #f4f6f9;
      font-family: Arial, sans-serif;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 1rem;
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #333;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 1rem;
    }

    .card {
      background: #fff;
      border-radius: 1rem;
      padding: 1rem;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .card h6 {
      margin: 0 0 0.5rem 0;
      font-size: 0.9rem;
      color: #666;
    }

    .value {
      font-size: 1.4rem;
      font-weight: bold;
      color: #222;
    }

    .rekom {
      margin-top: 2rem;
      background: #fff;
      border-radius: 1rem;
      padding: 1.5rem;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .rekom h5 {
      margin-top: 0;
    }

    .rekom ul {
      padding-left: 1.2rem;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Monitoring Kualitas Air</h2>

  <div class="grid">
    <div class="card">
      <h6>Suhu Air</h6>
      <div class="value"><?= $water_temp ?> °C</div>
    </div>
    <div class="card">
      <h6>TDS</h6>
      <div class="value"><?= $tds_ppm ?> ppm</div>
    </div>
    <div class="card">
      <h6>pH</h6>
      <div class="value"><?= $ph ?></div>
    </div>
    <div class="card">
      <h6>Salinitas</h6>
      <div class="value"><?= $salinity ?></div>
    </div>
    <div class="card">
      <h6>DO</h6>
      <div class="value"><?= $do_val ?></div>
    </div>
    <div class="card">
      <h6>Suhu Udara</h6>
      <div class="value"><?= $air_temp ?> °C</div>
    </div>
    <div class="card">
      <h6>Kelembaban</h6>
      <div class="value"><?= $air_hum ?> %</div>
    </div>
    <div class="card">
      <h6>Update Terakhir</h6>
      <div class="value"><?= $dateFormatted ?></div>
    </div>
  </div>

  <div class="rekom">
    <h5>Rekomendasi</h5>
    <ul>
      <li><?= $rekom_water_temp ?></li>
      <li><?= $rekom_tds ?></li>
      <li><?= $rekom_ph ?></li>
      <li><?= $rekom_salinity ?></li>
      <li><?= $rekom_do ?></li>
    </ul>
  </div>
</div>
</body>
</html>