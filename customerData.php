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
        <title>Customer Data - ARM-Settings</title>
        <link href='https://fonts.googleapis.com/css?family=Lato:400,700|Roboto+Slab:400,700|Inconsolata:400,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="./css/theme.css" type="text/css" />
        <link rel="stylesheet" href="./css/theme_extra.css" type="text/css" />
        <link rel="stylesheet" href="./css/highlight.css">
        <script>
            // Current page data
            var mkdocs_page_name = "Customer Data";
            var mkdocs_page_input_path = "index.md";
            var mkdocs_page_url = "/";
        </script>
        <script src="./js/jquery-2.1.1.min.js"></script>
        <script src="./js/modernizr-2.8.3.min.js"></script>
        <script type="text/javascript" src="./js/highlight.pack.js"></script> 
    </head>
    <body class="wy-body-for-nav" role="document">

        <style>
            .bg{display:none;position:fixed;width:100%;height:100%;background:#000;z-index:2;top:0;left:0;opacity:0.7;}
            .content{display:none;width:400px;height:100px;position:fixed;top:50%;margin-top:-150px;color:#F00;background:#000;z-index:3;left:50%;margin-left:-250px;padding:20px}
        </style>
        <div class="bg"></div>
        <div class="content">
            <li>Updating Is Runing.</li>
            <li>Don't Power Outages And Please Waiting is Over.</li>
            <li>Otherwise, The device is likely to be damaged.</li>
        </div>

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
                        <a class="current" href=".">Customer Data</a>
                        <ul>
                            <li class="toctree-l3"><a href="#welcome-to-arm-settings">Welcome to Customer Data</a></li>
                            <li class="toctree-l3"><a href="#update_application">Update Application</a></li>
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
                <a href=".">Customer Data</a>
            </nav>
            <div class="wy-nav-content">
            <div class="rst-content">
                <div role="navigation" aria-label="breadcrumbs navigation">
                    <ul class="wy-breadcrumbs">
                        <li><a href=".">Docs</a> &raquo;</li>
                        <li>Customer Data</li>
                        <li class="wy-breadcrumbs-aside">
                        </li>
                    </ul>
                    <hr/>
                </div>
                <div role="main">
                    <div class="section">
                        <h1 id="welcome-to-arm-settings">Welcome to Customer Data</h1>
                        <p>This Page just for ARM machine Customer Data settings.</p>
                        <hr/>
                        <div>
                          <div style="width:280px;">
                            <span style="float: left;">RemoteIP:</span>
                            <input style="float: right;text-align:center;" name="remoteIP" type="text" value=
                              <?php
                                  $command="grep 'IP = ' /usr/share/huishu/config.conf | head -n 1 | awk -F '=' '{print $2}'";
                                  $remoateIP = exec ($command);
                                  echo "\"".$remoateIP."\"";
                                  ?>
                              >
                            <br>
                            <div align="center" style="clear: both;"></div>
                          </div>

                          <div>
                              <?php
                                  // Parse with sections
                                  $ini_array = parse_ini_file("config.ini", true);
                                  print_r($ini_array["remote"]["ip"]."\n");
                                  while(current($ini_array)) {
                                      echo key($ini_array)."\n";
                                      next($ini_array);
                                  }
                                  print_r($ini_array);
                                  ?>
                          </div>

                        </div>
                        <div align="center" style="margin-top:20px;margin-bottom:20px">
                            <input type="button" onClick="javascript:customerDate()" value="Submit">
                        </div>

                        <h1 id="update_application">Update Application</h1>
                        <hr/>
                        <div>
                            <table >
                                <tr>
                                    <td>
                                        <span >FTP IP Address:</span>
                                    </td>
                                    <td>
                                        <input style="text-align:center;" name="ftpIPAddress" type="text" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span >Application File Name:</span>
                                    </td>
                                    <td>
                                        <input style="text-align: center;" name="ftpApplicationName" type="text" value="app.tar" />
                                    </td>
                                    <td>
                                        <input name="updateApp" type="button" onClick="javascript:updateApp()" value="Update Application">
                                    </td>
                                </tr>
                            </table>
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
        <script type="text/javascript" src="js/customerDataSettings.js"></script>
    </body>
</html>
<!--
MkDocs version : 0.16.0
Build Date UTC : 2016-12-13 01:26:17
-->

