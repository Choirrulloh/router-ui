<?php
$leases_file = '/var/lib/misc/dnsmasq.leases';
if (!file_exists($leases_file)) {
  $leases_file = 'dnsmasq.leases';
  if (!file_exists($leases_file)) {
    $leases_file = '../dnsmasq.leases';
  }
}
$leases = shell_exec('cat '.$leases_file);
$leases = (string)trim($leases);
$leases_arr = explode("\n", $leases);
foreach ($leases_arr as $lease) {
  $lease_arr = explode(" ", $lease);
  if ($lease_arr[0] == "duid") {
    break;
  }
  $display_symbol = "";
  if (strpos(strtolower($lease_arr[3]), 'iphone') !== false) {
    $display_symbol = "<i class='fab fa-apple'></i> <i class='fas fa-mobile'></i>";
  } else if (strpos(strtolower($lease_arr[3]), 'ipad') !== false) {
    $display_symbol = "<i class='fab fa-apple'></i> <i class='fas fa-tablet'></i>";
  } else if (strpos(strtolower($lease_arr[3]), 'macbook') !== false) {
    $display_symbol = "<i class='fab fa-apple'></i> <i class='fas fa-laptop'></i>";
  } else if (strpos(strtolower($lease_arr[3]), 'nokia') !== false
  || strpos(strtolower($lease_arr[3]), 'mobile') !== false || strpos(strtolower($lease_arr[3]), 'android') !== false) {
    $display_symbol = "<i class='fab fa-android'></i> <i class='fas fa-mobile-alt'></i>";
  } else if (strpos(strtolower($lease_arr[3]), 'airport') !== false) {
    $display_symbol = "<i class='fab fa-apple'></i> <i class='fas fa-wifi'></i>";
  } else if (strpos(strtolower($lease_arr[3]), 'linksys') !== false) {
    $display_symbol = "<i class='fas fa-wifi'></i>";
  } else if (strpos(strtolower($lease_arr[3]), 'deskjet') !== false || strpos(strtolower($lease_arr[3]), 'laserjet') !== false || strpos(strtolower($lease_arr[3]), 'printer') !== false) {
    $display_symbol = "<i class='fas fa-print'></i>";
  }
?>
<tr>
  <th scope="row"><?php echo $display_symbol ?> <?php echo $lease_arr[3] ?></th>
  <td><?php echo $lease_arr[1] ?></td>
  <td><?php echo $lease_arr[2] ?></td>
  <?php
  $epoch = $lease_arr[0];
  $dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
  ?>
  <td><?php echo $dt->format('D M d H:i'); ?></td>
</tr>
<?php } ?>
