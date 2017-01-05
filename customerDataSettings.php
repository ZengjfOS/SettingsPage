<?php header("Access-Control-Allow-Origin: *") ?>
<?php
    $data = json_decode(file_get_contents('php://input'), true);
    $categories = $data["categories"];
    $type = $data["type"];

    if ($categories == "customer" && $type == "configure") {
        $remoteIP = $data["remoteIP"];

        $result = exec("sed -i '0,/IP = .*/s/IP = .*/IP = ".$remoteIP."/' /usr/share/huishu/config.conf");

        if ($result == null){
            echo '{"status": "ok"}';
        } else {
            echo '{"status": "error"}';
        }

    }

    if ($categories == "customer" && $type == "updateApp") {
        $ftpIP = $data["ftpIPAddress"];
        $appName = $data["appName"];


        $ret = 0;
        system("rm /usr/share/huishu -rf");
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


