<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Include database connection
    require_once 'db_connection.php';

    //----------------------------------
     $str = file_get_contents(__DIR__ . "/dev_ds18b20.txt");
     if (preg_match('|t=([0-9]+)|mi', $str, $m)) {
         $temp = $m[1] / 1000;
         SaveMeasurement($conn, "28-00000b994082", $temp);
     }
    //----------------------------------
    
    function SaveMeasurement($conn, $dev_id, $temp) {
        try {
            
            $stmt = $conn->prepare("SELECT * FROM w1_devices WHERE dev_id = '$dev_id'");
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            #echo "Result: " . $result['w1_devices_id'] . "<br>";
            $stmt->close();

            if ($result === null) {
                die('You must first define the device.');
            } else {
                
                $stmt = $conn->prepare("SELECT * FROM temperature WHERE w1_devices_id = '$result[w1_devices_id]' ORDER BY date_insert DESC LIMIT 1");
                $stmt->execute();
                $latest = $stmt->get_result()->fetch_assoc();
                #echo "Latest: " . $latest['temperature'] . "<br>";
                $stmt->close();

                $ins = true;
                if ($latest !== null && abs($latest['temperature'] - $temp) < 1) {
                    $ins = false;
                    #echo "No need to insert<br>";
                    
                }

                if ($ins) {
                    echo $temp . " ";
                // Prepare the SQL query with placeholders
                    $sql = "INSERT INTO temperature (w1_devices_id, temperature) VALUES ('$result[w1_devices_id]','$temp')";
                    if ($conn->query($sql) === TRUE) {
                      #echo "New record created successfully";
                    } else {
                      echo "Error: " . $sql . "<br>" . $conn->error;
                    }

                }else {
                    echo $latest['temperature'] . " ";
                    $sql = "UPDATE temperature SET date_check = NOW() WHERE temperature_id = '$latest[temperature]'";
                    if ($conn->query($sql) === TRUE) {
                      #echo "New record updated successfully";
                    } else {
                      echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            }
        } catch (Exception $e) {
            echo "ERROR: " . $e->getMessage();
        }
        $conn->close();
    }
?>
