<?php
if (!$wan_interface) {
  $wan_interface = htmlspecialchars($_GET["interface"]);
}
echo shell_exec('vnstati -s --noheader --noedge -i '.$wan_interface.' -o ./summary.png');
?>
