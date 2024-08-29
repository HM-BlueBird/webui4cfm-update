<?php
// Function: makeTitle
function makeTitle($title) {
    echo "<h2>$title</h2>";
}
ini_set('display_errors', 0);
// Function: systemInfo
function systemInfo() {
    $android_version = shell_exec('getprop ro.build.version.release');
    $android_version = trim($android_version);
    $os = "Android $android_version";
    $distro = ""; // Customize for your environment if needed
    $hostname = php_uname('n');
    $kernel_info = php_uname('r');
    $uptime = shell_exec('cat /proc/uptime');
    $uptime = explode(' ', $uptime);
    $uptime_seconds = intval(trim($uptime[0]));
    $uptime_minutes = intval($uptime_seconds / 60 % 60);
    $uptime_hours = intval($uptime_seconds / 60 / 60 % 24);
    $uptime_days = intval($uptime_seconds / 60 / 60 / 24);

    $current_date = date('Y-m-d H:i:s');

    $device_model = shell_exec('getprop ro.product.model');
    $device_model = trim($device_model);

    $sim_operator = shell_exec('getprop gsm.sim.operator.alpha');
    $sim_operator = trim($sim_operator);

    echo "<tr><td>Device Model</td><td>$device_model</td></tr>";
    echo "<tr><td>OS</td><td>$os $distro</td></tr>";
    echo "<tr><td>SIM Operator</td><td>$sim_operator</td></tr>";
    echo "<tr><td>Hostname</td><td>$hostname</td></tr>";
    echo "<tr><td>Kernel</td><td>$kernel_info</td></tr>";
    echo "<tr><td>Uptime</td><td>$uptime_days days, $uptime_hours hours, $uptime_minutes minutes</td></tr>";
    echo "<tr><td>Current date</td><td>$current_date</td></tr>";


}

// Function: battery
function battery() {
    $battery_level = shell_exec('dumpsys battery | grep level | cut -d \':\' -f2');
    $ac_powered = shell_exec('dumpsys battery | grep AC | cut -d \':\' -f2');
    // $ac_powered = trim($ac_powered);

    echo "<tr><td>Battery Level</td><td>$battery_level%</td></tr>";
    echo "<tr><td>Charging AC</td><td>$ac_powered</td></tr>";
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Android Info</title>
    <style>
        body {
            background-color: #333333; /* Dark background */
            color: #ffffff; /* Light text */
            font-family: Arial, sans-serif; /* Example font */
            text-align: center; /* Center align text */
        }
        h2 {
            color: #ffffff; /* White text */
        }
        table {
            width: 80%; /* Adjust table width as needed */
            margin: 0 auto; /* Center align table */
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ffffff; /* White borders */
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #555555; /* Dark grey header */
        }
        tr:nth-child(even) {
            background-color: #666666; /* Darker grey */
        }
    </style>
</head>
<body>

<h1></h1>

<table id="info-table">
    <tr><th>Category</th><th>Details</th></tr>

    <?php
    // Call PHP functions to generate table rows
    systemInfo();
    battery();
    
    ?>

</table>
</body>
</html>

