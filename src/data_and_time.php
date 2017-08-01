<h2 id="dateAndTime">Date & Time</h2>
<div>
  <table>
    <tr>
      <td><span style="float: left;">Date:</span></td>
      <td>
        <input type="text" class="input" id="pickdate" style="float: right;text-align: center;color: #000;border-color: #ccc;cursor: pointer;" value=
            <?php
                $command=$MiniOS->configs["date_and_time"]["date"][$MiniOS->system_type];
                $date = exec ($command);
                echo "\"".$date."\"";
                ?>
        />
        <br>
        <div align="center" style="clear: both;"></div>
      </td>
      <td rowspan="2">
        <input name="dataAndTimeSubmit" type="button" onClick="javascript:setDataAndTime()" value="Submit">
      </td>
    </tr>
    <tr>
      <td><span style="float: left;">Time:</span></td>
      <td>
        <input type="text" class="input" id="picktime" style="float: right;text-align:center;color:#000;border-color: #ccc;cursor: pointer;" value=
            <?php
                $command=$MiniOS->configs["date_and_time"]["time"][$MiniOS->system_type];
                $time = exec ($command);
                echo "\"".$time."\"";
                ?>
        />
        <br>
        <div align="center" style="clear: both;"></div>
      </td>
    </tr>
  </table>
</div>
<hr/>
