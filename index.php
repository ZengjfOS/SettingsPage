<!DOCTYPE html>
<!--[if IE 8]>
    <html class="no-js lt-ie9" lang="en">
    <![endif]-->
    <!--[if gt IE 8]>
        <!-->
        <html class="no-js" lang="en">
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
            <script>// Current page data
                var mkdocs_page_name = "Home";
                var mkdocs_page_input_path = "index.md";
                var mkdocs_page_url = "/";</script>
            <script src="./js/jquery-2.1.1.min.js"></script>
            <script src="./js/modernizr-2.8.3.min.js"></script>
            <script type="text/javascript" src="./js/highlight.pack.js"></script>
        </head>
        
        <body class="wy-body-for-nav" role="document">
            <div class="wy-grid-for-nav">
                <nav data-toggle="wy-nav-shift" class="wy-nav-side stickynav">
                    <div class="wy-side-nav-search">
                        <a href="." class="icon icon-home">ARM-Settings</a>
                        <div role="search">
                            <form id="rtd-search-form" class="wy-form" action="./search.html" method="get">
                                <input type="text" name="q" placeholder="Search docs" /></form>
                        </div>
                    </div>
                    <div class="wy-menu wy-menu-vertical" data-spy="affix" role="navigation" aria-label="main navigation">
                        <ul class="current">
                            <li>
                                <li class="toctree-l1 current">
                                    <a class="current" href=".">Home</a>
                                    <ul>
                                        <li class="toctree-l3">
                                            <a href="#welcome-to-arm-settings">Welcome to ARM-Settings</a></li>
                                        <li>
                                            <a class="toctree-l4" href="#network">Network</a></li>
                                    </ul>
                                </li>
                                <li></ul>
                    </div>&nbsp;</nav>
                <section data-toggle="wy-nav-shift" class="wy-nav-content-wrap">
                    <nav class="wy-nav-top" role="navigation" aria-label="top navigation">
                        <i data-toggle="wy-nav-top" class="fa fa-bars"></i>
                        <a href=".">ARM-Settings</a></nav>
                    <div class="wy-nav-content">
                        <div class="rst-content">
                            <div role="navigation" aria-label="breadcrumbs navigation">
                                <ul class="wy-breadcrumbs">
                                    <li>
                                        <a href=".">Docs</a>&raquo;</li>
                                    <li>Home</li>
                                    <li class="wy-breadcrumbs-aside"></li>
                                </ul>
                                <hr/></div>
                            <div role="main">
                                <div class="section">
                                    <h1 id="welcome-to-arm-settings">Welcome to ARM-Settings</h1>
                                    <p>This Page just for ARM machine parameter settings.</p>
                                    <h2 id="network">Network</h2>
<script>
    function checkIP(ip) 
    { 
        obj=ip; 
        var exp=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/; 
        var reg = obj.match(exp); 
        if(reg==null) { 
            return false;//不合法 
        } else { 
            return true; //合法 
        } 
    } 
    
    function checkMask(mask) 
    { 
        obj=mask; 
        var exp=/^(254|252|248|240|224|192|128|0)\.0\.0\.0|255\.(254|252|248|240|224|192|128|0)\.0\.0|255\.255\.(254|252|248|240|224|192|128|0)\.0|255\.255\.255\.(254|252|248|240|224|192|128|0)$/; 
        var reg = obj.match(exp); 
        if(reg==null) { 
            return false; //"非法" 
        } else { 
            return true; //"合法" 
        } 
    } 

    function setNetworkConfigure()
    {
        var value = $('input[type="radio"][name="IPSettings"]:checked').val();
        console.info(value);

        var ajaxPostData = {};
        if (value == "DHCP"){
            console.info("execute DHCP.");
            ajaxPostData = {"categories":"network", "type": "dhcp"};
            console.info(ajaxPostData);
        } else {
            ip = $('input[name="ip"]').val();
            netmask = $('input[name="netmask"]').val();
            broadcast = $('input[name="broadcast"]').val();
            gateway = $('input[name="gateway"]').val();
            ajaxPostData = {"categories":"network", "type": "staticIP", "ip":ip, "netmask":netmask, "broadcast":broadcast, "gateway":gateway}
            console.info(ajaxPostData);

            if (checkIP(ip) == false) {
                alert("Please Check Your IP format.")
                return;
            }

            if (checkIP(netmask) == false) {
                alert("Please Check Your network format.")
                return;
            }
        }


        $.ajax({
            url: "settings.php",
            type: 'POST',
            contentType:'application/json; charset=utf-8',
            data: JSON.stringify(ajaxPostData),
            dataType:'json',
            success: function(data){
                //On ajax success do this
                console.info("success.");
                if (data["status"] == "ok"){
                    alert("Settings is Ok. The Machine is rebooting.");
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //On error do this
                console.info("error.");
                if (xhr.status == 200) {
        
                    alert(ajaxOptions);
                }
                else {
                    alert(xhr.status);
                    alert(thrownError);
                }
            }
        });
    }
</script>
<ul>
<input name="IPSettings" id="IPSettingsDHCP" type="radio" value="DHCP" checked><span id=aa onclick="IPSettingsDHCP.checked=true">DHCP</span>
<br>
<input name="IPSettings" id="IPSettingsStaticIP"type="radio" value="StaticIP"><span id=aa onclick="IPSettingsStaticIP.checked=true">Static IP</span>
<br>
<div id="staticSettingsAglinDiv" style="margin-left:40px;width:280px;height:140px;">
    <div>
        <span style="float: left;">IP:</span>
        <input style="float: right;" name="ip" type="text" value=
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
        <input style="float: right;" name="netmask" type="text" value=
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
        <input style="float: right;" name="broadcast" type="text" value=
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
        <input style="float: right;" name="gateway" type="text" value=
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
<div></ul>
</div>
                                    </div>
                                    <footer>
                                        <hr/>
                                        <div role="contentinfo">
                                            <!-- Copyright etc --></div>Built with
                                        <a href="http://www.mkdocs.org">MkDocs</a>using a
                                        <a href="https://github.com/snide/sphinx_rtd_theme">theme</a>provided by
                                        <a href="https://readthedocs.org">Read the Docs</a>.</footer></div>
                            </div>
                </section>
                </div>
                <div class="rst-versions" role="note" style="cursor: pointer">
                    <span class="rst-current-version" data-toggle="rst-current-version"></span>
                </div>
                <script src="./js/theme.js"></script>
        </body>
        
        </html>
        <!-- MkDocs version : 0.16.0 Build Date UTC : 2016-12-12 05:09:39 -->
