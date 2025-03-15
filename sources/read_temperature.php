<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Include database connection
    require_once 'db_connection.php';

    //----------------------------------
    $str = file_get_contents('/sys/bus/w1/devices/w1_bus_master1/w1_master_slaves');
    $dev_ds18b20 = preg_split("/\r\n|\r|\n/", $str);
    
    foreach ($dev_ds18b20 as $val) {
        if ($val != '') {
            $temp_path = "/sys/bus/w1/devices/$val/w1_slave";
            $str = file_get_contents($temp_path);
            if (preg_match('|t=([0-9]+)|mi', $str, $m)) {
                $temp = $m[1] / 1000;
                SaveMeasurement($conn, $val, $temp);
            }
        }
    }
    //----------------------------------
    
    function SaveMeasurement($conn, $dev_id, $temp) {
        try {
            $stmt = $conn->prepare("SELECT * FROM w1_devices WHERE dev_id = ?");
            $stmt->bind_param("s", $dev_id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($result === null) {
                die('You must first define the device.');
            } else {
                $stmt = $conn->prepare("SELECT * FROM temperature WHERE w1_devices_id = ? ORDER BY date_insert DESC LIMIT 1");
                $stmt->bind_param("i", $result['w1_devices_id']);
                $stmt->execute();
                $latest = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                $ins = true;
                if ($latest !== null && abs($latest['temperature'] - $temp) < 0.1) {
                    $ins = false;
                }

                if ($ins) {
                    $stmt = $conn->prepare("INSERT INTO temperature (w1_devices_id, temperature) VALUES (?, ?)");
                    $stmt->bind_param("id", $result['w1_devices_id'], $temp);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    $stmt = $conn->prepare("UPDATE temperature SET date_check = NOW() WHERE temperature_id = ?");
                    $stmt->bind_param("i", $latest['temperature_id']);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        } catch (Exception $e) {
            echo "ERROR: " . $e->getMessage();
        }
    }
?>
