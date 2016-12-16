<!DOCTYPE html>
<!--[if IE 8]>
<html class="no-js lt-ie9" lang="en" >
<![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js" lang="en" >
<!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="None">
        <meta name="author" content="aplexos">
        <link rel="shortcut icon" href="./img/favicon.ico">
        <title>Home - ARM-Settings</title>
        <link href='https://fonts.googleapis.com/css?family=Lato:400,700|Roboto+Slab:400,700|Inconsolata:400,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="./css/theme.css" type="text/css" />
        <link rel="stylesheet" href="./css/theme_extra.css" type="text/css" />
        <link rel="stylesheet" href="./css/highlight.css">
        <script>
            // Current page data
            var mkdocs_page_name = "Home";
            var mkdocs_page_input_path = "index.md";
            var mkdocs_page_url = "/";
        </script>
        <script src="./js/jquery-2.1.1.min.js"></script>
        <script src="./js/modernizr-2.8.3.min.js"></script>
        <script type="text/javascript" src="./js/highlight.pack.js"></script> 
    </head>
    <body class="wy-body-for-nav" role="document">
        <div class="wy-grid-for-nav">
        <nav data-toggle="wy-nav-shift" class="wy-nav-side stickynav">
            <div class="wy-side-nav-search">
                <a href="." class="icon icon-home"> ARM-Settings</a>
                <div role="search">
                    <form id ="rtd-search-form" class="wy-form" action="./search.html" method="get">
                        <input type="text" name="q" placeholder="Search docs" />
                    </form>
                </div>
            </div>
            <div class="wy-menu wy-menu-vertical" data-spy="affix" role="navigation" aria-label="main navigation">
                <ul class="current">
                    <li>
                    <li class="toctree-l1 current">
                        <a class="current" href=".">Home</a>
                        <ul>
                            <li class="toctree-l3"><a href="#welcome-to-arm-settings">Welcome to ARM-Settings</a></li>
                            <li><a class="toctree-l4" href="#base-infomation">Base Infomation</a></li>
                            <li><a class="toctree-l4" href="#network">Network</a></li>
                            <li><a class="toctree-l4" href="#dateAndTime">Date & Time</a></li>
                            <li><a class="toctree-l4" href="#show-data">Show Data</a></li>
                            <li><a class="toctree-l4" href="#customerdata">Customer Data</a></li>
                        </ul>
                    </li>
                    <li>
                </ul>
            </div>
            &nbsp;
        </nav>
        <section data-toggle="wy-nav-shift" class="wy-nav-content-wrap">
            <nav class="wy-nav-top" role="navigation" aria-label="top navigation">
                <i data-toggle="wy-nav-top" class="fa fa-bars"></i>
                <a href=".">ARM-Settings</a>
            </nav>
            <div class="wy-nav-content">
            <div class="rst-content">
                <div role="navigation" aria-label="breadcrumbs navigation">
                    <ul class="wy-breadcrumbs">
                        <li><a href=".">Docs</a> &raquo;</li>
                        <li>Home</li>
                        <li class="wy-breadcrumbs-aside">
                        </li>
                    </ul>
                    <hr/>
                </div>
                <div role="main">
                    <div class="section">
                        <h1 id="welcome-to-arm-settings">Welcome to ARM-Settings</h1>
                        <p>This Page just for ARM machine parameter settings.</p>
                        <hr/>
                        <h2 id="base-infomation">Base Infomation</h2>
                        <table border="1">
                        <tr>
                            <td>CPU Info:</td>
                            <td>
                            <?php
                                $command="grep Hardware /proc/cpuinfo | awk -F ':' '{print $2}' | sed 's/ Sabre-SD Board//g'";
                                $cpuInfo = exec ($command);
                                echo $cpuInfo;
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Processor Info:</td>
                            <td>
                            <?php
                                $command="grep Processor /proc/cpuinfo | head -n 1 | awk -F ':' '{ print $2}'";
                                $processorInfo = exec ($command);
                                echo $processorInfo;
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Memery Info:</td>
                            <td>
                            <?php
                                $command="grep MemTotal /proc/meminfo | awk -F ':' '{print $2}' | awk -F ' ' '{print $1}'";
                                $memeryInfo = exec ($command);
                                echo strval(intval($memeryInfo)/1000)." MB";
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>eMMC Info:</td>
                            <td>
                            <?php
                                $command="fdisk -l | grep mmcblk0: | awk -F ':' '{print $2}' | awk -F ',' '{print $1}'";
                                $eMMCInfo = exec ($command);
                                echo $eMMCInfo;
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>eMMC Available:</td>
                            <td>
                            <?php
                                $command="df -h | grep /dev/root | awk -F ' ' '{print $4}'";
                                $eMMCAvailable= exec ($command);
                                echo $eMMCAvailable;
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Operating System Type:</td>
                            <td>
                            <?php
                                $command="uname";
                                $systemVersion= exec ($command);
                                echo $systemVersion;
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Linux Version:</td>
                            <td>
                            <?php
                                $command="uname -a | awk -F ' ' '{print $3}'";
                                $systemVersion= exec ($command);
                                echo $systemVersion;
                            ?>
                            </td>
                        </tr>
                        </table>
                        <hr/>
                        <h2 id="network">Network</h2>
                        <ul>
                            <!-- for data & time -->
                            <link rel="stylesheet" type="text/css" href="css/datedropper.css">
                            <link rel="stylesheet" type="text/css" href="css/timedropper.min.css">
                            <script src="js/datedropper.min.js"></script>
                            <script src="js/timedropper.min.js"></script>

                            <!-- Network -->
                            <li> Configure </li>
                            <div style="margin-left:40px;">
                                <div onClick="javascript:dhcpRadioClick()" style="width:80px;">
                                    <input name="IPSettings" id="IPSettingsDHCP" type="radio" value="DHCP" 
                                    <?php
                                        $command="grep 'iface eth0 inet dhcp' /etc/network/interfaces";
                                        $dhcpGetIP = exec ($command);
                                        if ($dhcpGetIP != null) {
                                            echo "checked";
                                        }
                                    ?>
                                    ><span id=aa onClick="IPSettingsDHCP.checked=true">DHCP</span>
                                </div>
                                <div onClick="javascript:staticIPRadioClick()" style="width:80px;">
                                    <input name="IPSettings" id="IPSettingsStaticIP"type="radio" value="StaticIP"
                                    <?php
                                        $command="grep 'iface eth0 inet dhcp' /etc/network/interfaces";
                                        $dhcpGetIP = exec ($command);
                                        if ($dhcpGetIP == null) {
                                            echo "checked";
                                        }
                                    ?>
                                    ><span id=aa onClick="IPSettingsStaticIP.checked=true">Static IP</span>
                                </div>
                                <div id="staticSettingsAglinDiv" style="margin-left:40px;width:280px;height:140px;">
                                    <div>
                                        <span style="float: left;">IP:</span>
                                        <input style="float: right;text-align:center;" name="ip" type="text" value=
                                            <?php
                                                $command="ifconfig 'eth0' | grep 'inet ' | awk -F ' ' '{print $2}'";
                                                $localIP = exec ($command);
                                                echo "\"".$localIP."\"";
                                                ?>
                                            >
                                        <br>
                                        <div align="center" style="clear: both;"></div>
                                    </div>
                                    <div>
                                        <span style="float: left;">Netmask:</span>
                                        <input style="float: right;text-align:center;" name="netmask" type="text" value=
                                            <?php
                                                $command="ifconfig 'eth0' | grep 'inet ' | awk -F ' ' '{print $4}'";
                                                $localIP = exec ($command);
                                                echo "\"".$localIP."\"";
                                                ?>
                                            >
                                        <br>
                                        <div align="center" style="clear: both;"></div>
                                    </div>
                                    <div>
                                        <span style="float: left;">Broadcast:</span>
                                        <input style="float: right;text-align:center;" name="broadcast" type="text" value=
                                            <?php
                                                $command="ifconfig 'eth0' | grep 'inet ' | awk -F ' ' '{print $6}'";
                                                $localIP = exec ($command);
                                                echo "\"".$localIP."\"";
                                                ?>
                                            >
                                        <br>
                                        <div align="center" style="clear: both;"></div>
                                    </div>
                                    <div>
                                        <span style="float: left;">Gateway:</span>
                                        <input style="float: right;text-align:center;" name="gateway" type="text" value=
                                            <?php
                                                $command="route -n | grep UG | head -n  1 | awk -F ' ' '{print $2}'";
                                                $localIP = exec ($command);
                                                echo "\"".$localIP."\"";
                                                ?>
                                            >
                                        <br>
                                        <div align="center" style="clear: both;"></div>
                                    </div>
                                </div>
                            <div align="center" style="margin-top:20px;margin-bottom:20px">
                                <input name="networkSubmit" type="button" onClick="javascript:setNetworkConfigure()" value="Submit">
                            </div>
                            </div>
                            <li>Check the WAN network</li> 
                            <div align="left" style="margin-left:40px;"> 
                                <div style="width:280px;">
                                    <span style="float: left;">IP or DNS:</span>
                                    <input style="float: right;text-align:center;" name="pingNetWork" type="text">
                                    <br>
                                </div>
                                <div align="center" style="clear: both;"></div>
                            </div>
                            <div align="center" style="margin-top:20px;margin-bottom:20px">
                                <input type="button" onClick="javascript:pingNetWork()" value="Ping">
                            </div>
                        </ul>
                        <hr/>
                        <h2 id="dateAndTime">Date & Time</h2>
                        <div>
                            <div style="width:220px;">
                                <div>
                                    <span style="float: left;">Date:</span>
                                    <input type="text" class="input" id="pickdate" style="float: right;text-align: center;color: #000;border-color: #ccc;cursor: pointer;" value=
                                        <?php
                                            $command="date '+%Y-%m-%d'";
                                            $date = exec ($command);
                                            echo "\"".$date."\"";
                                            ?>
                                    />
                                    <br>
                                    <div align="center" style="clear: both;"></div>
                                </div>
                                <div>
                                    <span style="float: left;">Time:</span>
                                    <input type="text" class="input" id="picktime" style="float: right;text-align:center;color:#000;border-color: #ccc;cursor: pointer;" value=
                                        <?php
                                            $command="date '+%H:%M'";
                                            $time = exec ($command);
                                            echo "\"".$time."\"";
                                            ?>
                                    />
                                    <br>
                                    <div align="center" style="clear: both;"></div>
                                </div>
                            </div>
                            <div align="center" style="margin-top:20px;margin-bottom:20px">
                                <input name="dataAndTimeSubmit" type="button" onClick="javascript:setDataAndTime()" value="Submit">
                            </div>
                        </div>
                        <hr/>
                        <h2 id="show-data">Show Data</h2>
                        <p><a href="sensor.php">Show sensor data with GUI.</a></p>
                        <h2 id="show-data">Configure customer data</h2>
                        <p><a href="customerData.php">Configure customer data</a></p>
                        </div>
                        </div>
                        <footer>
                            <hr/>
                            <div role="contentinfo">
                                <!-- Copyright etc -->
                            </div>
                            Built with <a href="http://www.mkdocs.org">MkDocs</a> using a <a href="https://github.com/snide/sphinx_rtd_theme">theme</a> provided by <a href="https://readthedocs.org">Read the Docs</a>.
                        </footer>
                    </div>
                </div>
        </section>
        </div>
        <div class="rst-versions" role="note" style="cursor: pointer">
            <span class="rst-current-version" data-toggle="rst-current-version">
            </span>
        </div>
        <script src="./js/theme.js"></script>

        <!-- for Settings -->
        <script type="text/javascript" src="js/dataSettings.js"></script>
    </body>
</html>
<!--
MkDocs version : 0.16.0
Build Date UTC : 2016-12-13 01:26:17
-->
