<?php
    $data = json_decode(file_get_contents('php://input'), true);
    $categories = $data["categories"];
    $type = $data["type"];

    if ($categories == "network") {
        $networkFile = fopen("/etc/network/interfaces", "w+");
        $fileString = "";
        if ($type == "dhcp") {
            $fileString = "auto lo \niface lo inet loopback\n\n#dhcp \nauto eth0\niface eth0 inet dhcp\n";
        } else {
            $fileString = $fileString ."auto lo \niface lo inet loopback\n\n";
            $fileString = $fileString ."auto eth0\niface eth0 inet static\n";
            $fileString = $fileString ."    address ".$data["ip"]."\n";
            $fileString = $fileString ."    netmask ".$data["netmask"]."\n";
            $fileString = $fileString ."    broadcast ".$data["broadcast"]."\n";
            $fileString = $fileString ."    gateway ".$data["gateway"]."\n";
        }
        fwrite($networkFile, $fileString);
        fclose($networkFile);
        
        echo "{'status': 'ok'}";
        
    	shell_exec('sync');
    	shell_exec('reboot');
    }

    shell_exec('sync');
?>


