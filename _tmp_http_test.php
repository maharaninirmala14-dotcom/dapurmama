<?php
// Tes end-to-end: login admin via HTTP lalu buka /admin/apriori-proof

$cookie = tempnam(sys_get_temp_dir(), 'ck');

// 1. Ambil halaman login (ambil token CSRF + cookie sesi)
$ch = curl_init('http://127.0.0.1:8000/login');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR => $cookie,
    CURLOPT_COOKIEFILE => $cookie,
]);
$loginPage = curl_exec($ch);
curl_close($ch);

preg_match('/name="_token" value="([^"]+)"/', $loginPage, $m);
$token = $m[1] ?? null;
echo 'CSRF token: ' . ($token ? 'OK' : 'GAGAL') . PHP_EOL;

// 2. Login sebagai admin
$ch = curl_init('http://127.0.0.1:8000/login');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_COOKIEJAR => $cookie,
    CURLOPT_COOKIEFILE => $cookie,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_POSTFIELDS => http_build_query([
        '_token' => $token,
        'email' => 'admindelisha@gmail.com',
        'password' => 'admindelisha_04',
    ]),
]);
$resp = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
echo 'Login status: ' . $info['http_code'] . ' -> ' . ($info['redirect_url'] ?: '(none)') . PHP_EOL;

// 3. Buka halaman Bukti Apriori
$ch = curl_init('http://127.0.0.1:8000/admin/apriori-proof');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR => $cookie,
    CURLOPT_COOKIEFILE => $cookie,
    CURLOPT_FOLLOWLOCATION => true,
]);
$page = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo 'Proof page status: ' . $info['http_code'] . PHP_EOL;
echo 'Length: ' . strlen($page) . PHP_EOL;

$checks = [
    'Judul' => 'Bukti Kerja Algoritma Apriori',
    'Nirmala' => 'Nirmala Maharani',
    'Nirmanto' => 'Nirmanto Wijaya',
    'Kesimpulan' => 'Kesimpulan Perbandingan',
    'Rule top Nirmala' => 'Nasi Ayam Bakar',
    'Rule top Nirmanto' => 'Samosa',
    'Sidebar link' => 'apriori-proof',
    'Error PHP' => 'Exception',
];
foreach ($checks as $label => $needle) {
    echo str_pad($label, 20) . ': ' . (str_contains($page, $needle) ? 'KETEMU' : 'tidak ada') . PHP_EOL;
}

// tangkap error PHP yang mungkin muncul di body
if (preg_match('/(Whoops|Fatal error|Exception|SQLSTATE)/', $page, $m)) {
    echo '!!! ERROR TERDETEKSI: ' . $m[1] . PHP_EOL;
    // ambil sekitarnya
    $pos = strpos($page, $m[1]);
    echo substr($page, max(0, $pos - 200), 600) . PHP_EOL;
}

unlink($cookie);
