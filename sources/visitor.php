<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Function to get the real IP address of the visitor
    function getUserIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    // Get visitor's public IP
    function getPublicIP() {
        $ip = file_get_contents('https://api64.ipify.org?format=json');
        $data = json_decode($ip, true);
        return $data['ip'] ?? 'Unknown';
    }

    $public_ip = getPublicIP();
    $visitor_ip = getUserIP();

    // Log the IP address to a file
    $log_file = 'visitors.log';
    $log_entry = date('Y-m-d H:i:s') . " - PrivateIP: " . $visitor_ip . " - PublicIP: ". $public_ip . "\n";

    file_put_contents($log_file, $log_entry, FILE_APPEND);

    //echo " \" " . htmlspecialchars($visitor_ip) . " - " . htmlspecialchars($public_ip) ." \" " ;
?>
