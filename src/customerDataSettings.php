<?php header("Access-Control-Allow-Origin: *") ?>
<?php
    $data = json_decode(file_get_contents('php://input'), true);
    $categories = $data["categories"];
    $type = $data["type"];

    if ($categories == "customer" && $type == "configure") {

        $confFile = fopen("/etc/aplex/config.ini", "w+");
        $fileString = "";

        $fileString .= "[remote]\n";
        $fileString .= "ip = ".$data["remote"]["ip"]."\n";
        $fileString .= "port = ".$data["remote"]["port"]."\n";
        $fileString .= "user = ".$data["remote"]["user"]."\n";
        $fileString .= "password = ".$data["remote"]["password"]."\n";
        $fileString .= "database = ".$data["remote"]["database"]."\n";
        $fileString .= "table = ".$data["remote"]["table"]."\n";
        $fileString .= "[localhost]\n";
        $fileString .= "ip = ".$data["localhost"]["ip"]."\n";
        $fileString .= "port = ".$data["localhost"]["port"]."\n";
        $fileString .= "user = ".$data["localhost"]["user"]."\n";
        $fileString .= "password = ".$data["localhost"]["password"]."\n";
        $fileString .= "database = ".$data["localhost"]["database"]."\n";
        $fileString .= "table = ".$data["localhost"]["table"]."\n";
        $fileString .= "[interval]\n";
        $fileString .= "heartbeat = ".$data["interval"]["heartbeat"]."\n";
        $fileString .= "upload_data = ".$data["interval"]["upload_data"]."\n";

        $result = fwrite($confFile, $fileString);
        fclose($confFile);

        shell_exec('sync');

        if ($result != null && $result > 0){
            echo '{"status": "ok"}';
        } else {
            echo '{"status": "error"}';
        }

    }

    if ($categories == "customer" && $type == "updateApp") {
        $ftpIP = $data["ftpIPAddress"];
        $appName = $data["appName"];


        $ret = 0;
        system("rm /usr/share/customer -rf");
        echo 
        system("ftpget ".$ftpIP." /root/".$appName." ".$appName." && tar xf /root/".$appName." -C /usr/share && reboot;", $ret);
        if ($ret == 0){
            echo '{"status": "ok"}';
        } else {
            echo '{"status": "error"}';
        }

    }

    shell_exec('sync');
?>


