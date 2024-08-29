<?php
$moduledir = "../adb/modules/ClashForMagisk";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = file_get_contents("php://input");
    // Lakukan sesuatu dengan data yang diterima, contohnya disimpan dalam database
    // Di sini, kita hanya akan mengirim kembali data yang diterima sebagai respons
    header("Content-Type: application/json");
    $data =  json_decode($data);
    if ($data->func === 'reboot') {
        shell_exec('am start -a android.intent.action.REBOOT');
        echo json_encode("rebootResponse");
    } else if ($data->func === "shutdown") {
        shell_exec('am start -a com.android.internal.intent.action.REQUEST_SHUTDOWN');
        echo json_encode("shutdownResponse");
    } else if ($data->func === "signal") {
        shell_exec('service call phone 27');
        echo json_encode("signalExecResponse");
    } else if ($data->func === "yacdOn") {
        unlink("$moduledir/disable");
        echo json_encode("yacdOnResponse");
    } else if ($data->func === "yacdOff") {
        $myfile = fopen("$moduledir/disable", "w") or die("Unable to open file!");
        echo json_encode("yacdOffResponse");
    } else {
        echo json_encode("err");
    }
} else {

    //signal meter
    $signaldb = shell_exec('dumpsys telephony.registry | grep -i signalstrength | cut -d " " -f 12 | head -n 1');
    $signal = "Loss";
    if ($signaldb <= -110 && $signaldb >= -140) {
        $signal = "Very Poor";
    } else if ($signaldb <= -100 && $signaldb >= -109) {
        $signal = "Poor";
    } else if ($signaldb <= -90 && $signaldb >= -99) {
        $signal = "Average";
    } else if ($signaldb <= -80 && $signaldb >= -89) {
        $signal = "Good";
    } else if ($signaldb <= -50 && $signaldb >= -79) {
        $signal = "Great";
    } else if ($signaldb >= 0) {
        $signaldb = "";
    }
    //is radio on
    $isradioon = shell_exec('service call phone 14');
    if (str_contains($isradioon, '01')) {
        $isradioon = true;
    } else {
        $isradioon = false;
    }
    //is yacd on
    $pid = "run/clash.pid";
    if (file_exists($pid)) {
        $yacd = true;
    } else {
        $yacd = false;
    }
    //clash log
    $clashlogs = "run/run.logs";
    $file = fopen("$clashlogs", "r");
    $log = "";
    while (!feof($file)) {
        $logtmp = str_replace('"', '', fgets($file));
        $log .= nl2br($logtmp);
    }
    //$log = str_replace("\r\n", "", $log);
    
    // cari ip
    $ip = rtrim($_SERVER['HTTP_HOST'], "9999");
    
    // sms
    $smsDB = shell_exec('
    su
    DB=/data/data/com.android.providers.telephony/databases/mmssms.db
    echo "select address,body from sms ORDER BY ROWID;" | sqlite3 -csv $DB
    ');
    
    $pattern = '/\n|,"\s*/';
    $sms = preg_split($pattern, $smsDB);
    
    // Menghilangkan tanda kutip yang tertinggal di awal setiap elemen array
    $sms = array_map(function($item) {
        return ltrim($item, '"');
    }, $sms);
    
    $battery = shell_exec('dumpsys battery | grep "level" | sed "s/[^0-9]//g"');
    
    $conndevice = shell_exec('ip neigh show dev wlan0 | grep "REACHABLE" | wc -l');
    
    //pass data
    $data = array($signaldb." ".$signal, $isradioon, $yacd, $log, $ip,$sms,$battery,$conndevice);
    
    header("Content-Type: application/json");
    echo json_encode($data);
}

