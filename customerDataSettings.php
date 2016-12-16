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

    shell_exec('sync');
?>


