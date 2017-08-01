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
    <table>
      <tr>
        <td>
          <div onClick="javascript:dhcpRadioClick()" style="width:80px;">
              <input name="IPSettings" id="IPSettingsDHCP" type="radio" value="DHCP" 
              <?php
                  $command=$MiniOS->configs["network"]["dhcp"]["check_mode"][$MiniOS->system_type];
                  $dhcpGetIP = exec ($command);
                  if ($dhcpGetIP != null) {
                      echo "checked";
                  }
              ?>
              ><span id=aa onClick="IPSettingsDHCP.checked=true">DHCP</span>
          </div>
        </td>
        <td style="width:280px;"></td>
        <td rowspan="2">
          <input name="networkSubmit" type="button" onClick="javascript:setNetworkConfigure()" value="Submit">
        </td>
      </tr>
      <tr>
        <td>
          <div onClick="javascript:staticIPRadioClick()" style="width:80px;">
              <input name="IPSettings" id="IPSettingsStaticIP"type="radio" value="StaticIP"
              <?php
                  $command=$MiniOS->configs["network"]["static"]["check_mode"][$MiniOS->system_type];
                  $dhcpGetIP = exec ($command);
                  if ($dhcpGetIP == null) {
                      echo "checked";
                  }
              ?>
              ><span id=aa onClick="IPSettingsStaticIP.checked=true">Static IP</span>
          </div>
        </td>
        <td>
          <div id="staticSettingsAglinDiv" style="width:280px;height:140px;">
              <div>
                  <span style="float: left;">IP:</span>
                  <input style="float: right;text-align:center;" name="ip" type="text" value=
                      <?php
                          $command=$MiniOS->configs["network"]["static"]["ip"][$MiniOS->system_type];
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
                          $command=$MiniOS->configs["network"]["static"]["netmask"][$MiniOS->system_type];
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
                          $command=$MiniOS->configs["network"]["static"]["broadcast"][$MiniOS->system_type];
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
                          $command=$MiniOS->configs["network"]["static"]["gateway"][$MiniOS->system_type];
                          $localIP = exec ($command);
                          echo "\"".$localIP."\"";
                          ?>
                      >
                  <br>
                  <div align="center" style="clear: both;"></div>
              </div>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <li>Check the WAN network</li> 
  <div style="margin-left:40px;">
    <table>
      <tr>
        <td>
          <span style="float: left;">IP or DNS:</span>
        </td>
        <td>
          <input style="float: right;text-align:center;" name="pingNetWork" type="text">
        </td>
        <td>
          <input type="button" onClick="javascript:pingNetWork()" value="Ping">
        </td>
      </tr>
    </table>
  </div>
</ul>
<hr/>
