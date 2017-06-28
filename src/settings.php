<?php header("Access-Control-Allow-Origin: *") ?>
<?php
    $data = json_decode(file_get_contents('php://input'), true);
    $categories = $data["categories"];
    $type = $data["type"];

    if ($categories == "network") {
        if ($type == "dhcp" || $type == "staticIP") {
            $networkFile = fopen("/etc/network/interfaces", "w+");
            $fileString = "";
            if ($type == "dhcp") {
                $fileString = "auto lo \niface lo inet loopback\n\n#dhcp \nauto eth0\niface eth0 inet dhcp\n";
            } elseif ($type == "staticIP"){
                $fileString = $fileString ."auto lo \niface lo inet loopback\n\n";
                $fileString = $fileString ."auto eth0\niface eth0 inet static\n";
                $fileString = $fileString ."    address ".$data["ip"]."\n";
                $fileString = $fileString ."    netmask ".$data["netmask"]."\n";
                $fileString = $fileString ."    broadcast ".$data["broadcast"]."\n";
                $fileString = $fileString ."    gateway ".$data["gateway"]."\n";
            } 
            fwrite($networkFile, $fileString);
            fclose($networkFile);
            
            echo '{"status": "ok"}';
            
            shell_exec('sync');
            shell_exec('reboot');
        }

        if ($type == "ping") {
            $IPOrDNS = $data["IPOrDNS"];
            $result = exec("ping -c 1 '".$IPOrDNS."' 2>&1 | grep '0% packet loss,'");
            if ($result != null)
                echo '{"status": "ok"}';
            else
                echo '{"status": "error"}';
        }
    }

    if ($categories == "dateAndTime" && $type == "dateAndTime") {
        $date = $data["date"];
        $time = $data["time"];

        $result = exec("date -s '".$date." ".$time."'");

        if (strpos($result, $time)){
            exec("hwclock -w");
            echo '{"status": "ok"}';
        } else {
            echo '{"status": "error"}';
        }

    }

    if ($categories == "updateSystem"){
        
        if ($type == "uboot") {
            $ftpIP = $data["ftpIP"];
            $ubootName = $data["ubootName"];

            $ret = 0;
            system("ftpget ".$ftpIP." /root/".$ubootName." ".$ubootName." && dd if=/dev/zero of=/dev/mmcblk0 bs=512 seek=2 count=2000 && dd if=/root/".$ubootName." of=/dev/mmcblk0 bs=512 seek=2 skip=2", $ret);

            if ($ret == 0){
                echo '{"status": "ok"}';
            } else {
                echo '{"status": "error"}';
            }
        } else if ($type == "kernel") {
            $ftpIP = $data["ftpIP"];
            $kernelName = $data["kernelName"];

            $ret = 0;
            system("ftpget ".$ftpIP." /root/".$kernelName." ".$kernelName." && dd if=/root/".$kernelName." of=/dev/mmcblk0 bs=1M seek=1 conv=fsync", $ret);

            if ($ret == 0){
                echo '{"status": "ok"}';
            } else {
                echo '{"status": "error"}';
            }
            
        } else if ($type == "rootfs") {
            $ftpIP = $data["ftpIP"];
            $rootfsName = $data["rootfsName"];

            $ret = 0;
            system("umount /usr/share/initramfs/mnt");
            system("ftpget ".$ftpIP." /root/".$rootfsName." ".$rootfsName." && mount -t ext3 /dev/mmcblk0p1 /usr/share/initramfs/mnt/ && chroot /usr/share/initramfs /etc/update", $ret);

            if ($ret == 0){
                echo '{"status": "ok"}';
            } else {
                echo '{"status": "error"}';
            }

        }
    }

    shell_exec('sync');
?>


