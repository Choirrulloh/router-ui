<?php
$load = sys_getloadavg();
echo number_format((float)$load[0], 2, '.', '').", ".number_format((float)$load[1], 2, '.', '').", ".number_format((float)$load[3], 2, '.', '');
?>
