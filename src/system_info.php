<h2 id="base-infomation">Base Infomation</h2>
<table border="1">
<?php
        foreach ($MiniOS->configs["system_info"] as $key => $value) {
            echo "<tr>";
            echo "  <td>".$key.":</td>\n";
            $ret = exec($value[$MiniOS->system_type]);
            echo "  <td>".$ret."</td>\n";
            echo "</tr>";
        }
?>
</table>
<hr/>
