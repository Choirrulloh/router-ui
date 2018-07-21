<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link rel="stylesheet" href="fontawesome-free-5.1.1-web/css/all.min.css">

    <?php
    $wan_interface = "wan0";
    $lan_interface = "br-lan";
    $connectivity_test_host = "www.archlinux.org";
    ?>

    <?php $connected = @fsockopen($connectivity_test_host, 80); ?>

    <title><?php echo gethostname(); ?></title>
  </head>
  <body>
    <div class="container">
      <h1 style="text-align: center;">Chitnis Router</h1>
      <div class="row">
        <div class="col-3 border border-right-0 border-bottom-0">
          <strong><i class="fas fa-server"></i> System Name</strong>
        </div>
        <div class="col border border-bottom-0">
          <?php echo gethostname(); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-3 border border-right-0 border-bottom-0">
          <strong><i class="fab fa-linux"></i> OS</strong>
        </div>
        <div class="col border border-bottom-0">
          <?php echo php_uname(); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-3 border border-right-0 border-bottom-0">
          <strong><i class="fas fa-clock"></i> System Time</strong>
        </div>
        <div class="col border border-bottom-0">
          <?php echo shell_exec('date'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-3 border border-right-0 border-bottom-0">
          <strong><i class="fas fa-arrow-up"></i> Uptime</strong>
        </div>
        <div class="col border border-bottom-0">
          <?php echo shell_exec('uptime -p'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-3 border border-right-0 border-bottom-0">
          <strong><i class="fas fa-microchip"></i> System Load</strong>
        </div>
        <div class="col border border-bottom-0">
          <?php $load = sys_getloadavg(); echo number_format((float)$load[0], 2, '.', '').", ".number_format((float)$load[1], 2, '.', '').", ".number_format((float)$load[3], 2, '.', ''); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-3 border border-right-0 border-bottom-0">
          <strong><i class="fas fa-memory"></i> Memory Usage</strong>
        </div>
        <div class="col border border-bottom-0" style="padding-top: 5px; padding-bottom: 5px;">
          <?php
          $free = shell_exec('free');
          $free = (string)trim($free);
          $free_arr = explode("\n", $free);
          $mem = explode(" ", $free_arr[1]);
          $mem = array_filter($mem);
          $mem = array_merge($mem);
          $memory_usage_percent = 0;
          if ($mem[1] > 0) {
            $memory_usage_percent = $mem[2]/$mem[1]*100;
            $memory_usage_percent = number_format((float)$memory_usage_percent, 2, '.', '');
          }
          $memory_total_mb = number_format((float)$mem[1]/1024, 2, '.', '');
          $memory_used_mb = number_format((float)$mem[2]/1024, 2, '.', '');
          ?>
          <?php echo $memory_used_mb."/".$memory_total_mb." MB" ?>
          <div class="progress" style="height: 24px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $memory_usage_percent ?>%;" aria-valuenow="<?php echo $memory_usage_percent ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $memory_usage_percent ?>%</div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-3 border border-right-0 border-bottom-0">
          <strong><i class="fas fa-hdd"></i> Disk Usage</strong>
        </div>
        <div class="col border border-bottom-0" style="padding-top: 5px; padding-bottom: 5px;">
          <?php $disk_total = disk_total_space("/"); $disk_free = disk_free_space("/"); $disk_used = $disk_total - $disk_free; echo number_format((float)(((($disk_used)/1024)/1024)/1024), 2, '.', '')."/".number_format((float)((($disk_total/1024)/1024)/1024), 2, '.', '')." GB" ?>
          <?php $disk_percentage = ($disk_used/$disk_total)*100; $formatted_disk_percent = number_format((float)$disk_percentage, 2, '.', ''); ?>
          <div class="progress" style="height: 24px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $formatted_disk_percent ?>%;" aria-valuenow="<?php echo $formatted_disk_percent ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $formatted_disk_percent ?>%</div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col border border-bottom-0" style="padding-top: 20px; padding-bottom: 5px;">
          <h4 style="text-align: center;">Network</h4>
        </div>
      </div>
      <div class="row">
        <div class="col-3 border border-right-0 border-bottom-0">
          <strong><i class="fas fa-globe-americas"></i> WAN</strong>
        </div>
        <div class="col border border-bottom-0">
          <?php
          $lan_mac = shell_exec("ip addr show dev ".$wan_interface." | grep -w ether | awk '{ print $2 }'");
          $lan_ipv4 = shell_exec("ip addr show dev ".$wan_interface." | grep -w inet | grep -v 127.0.0.1 | awk '{ print $2}'");
          $lan_ipv4 = (string)trim($lan_ipv4);
          $lan_ipv4_arr = explode("\n", $lan_ipv4);
          $lan_ipv6 = shell_exec("ip addr show dev ".$wan_interface." | grep -w inet6 | grep -v ::1 | awk '{ print $2}'");
          $lan_ipv6 = (string)trim($lan_ipv6);
          $lan_ipv6_arr = explode("\n", $lan_ipv6);
          ?>
          <small><strong>Internet Connectivity:</strong>&nbsp;
            <?php
            if ($connected) {
              echo "<span class='text-success'><i class='fas fa-check-circle'></i>&nbsp;Connected</span>";
            } else {
              echo "<span class='text-danger'><i class='fas fa-times-circle'></i>&nbsp;Disconnected</span>";
            }
            ?>
          </small><br />
          <small><strong>Physical Address:</strong>&nbsp;<?php echo $lan_mac ?></small><br />
          <small><strong>IPv4:</strong>&nbsp;
            <?php
            foreach ($lan_ipv4_arr as $ipv4) {
              echo $ipv4."&nbsp;";
            }
            ?>
          </small><br />
          <small><strong>IPv6:</strong>&nbsp;
            <?php
            foreach ($lan_ipv6_arr as $ipv6) {
              if (substr($ipv6, 0, 4 ) === "fe80") {
                continue;
              }
              echo $ipv6."&nbsp;";
            }
            ?>
          </small>
        </div>
      </div>
      <div class="row">
        <div class="col-3 border border-right-0 border-bottom-0">
          <strong><i class="fas fa-wifi"></i> LAN</strong>
        </div>
        <div class="col border border-bottom-0">
          <?php
          $lan_mac = shell_exec("ip addr show dev ".$lan_interface." | grep -w ether | awk '{ print $2 }'");
          $lan_ipv4 = shell_exec("ip addr show dev ".$lan_interface." | grep -w inet | grep -v 127.0.0.1 | awk '{ print $2}'");
          $lan_ipv4 = (string)trim($lan_ipv4);
          $lan_ipv4_arr = explode("\n", $lan_ipv4);
          $lan_ipv6 = shell_exec("ip addr show dev ".$lan_interface." | grep -w inet6 | grep -v ::1 | awk '{ print $2}'");
          $lan_ipv6 = (string)trim($lan_ipv6);
          $lan_ipv6_arr = explode("\n", $lan_ipv6);
          ?>
          <small><strong>Physical Address:</strong>&nbsp;<?php echo $lan_mac ?></small><br />
          <small><strong>IPv4:</strong>&nbsp;
            <?php
            foreach ($lan_ipv4_arr as $ipv4) {
              echo $ipv4."&nbsp;";
            }
            ?>
          </small><br />
          <small><strong>IPv6:</strong>&nbsp;
            <?php
            foreach ($lan_ipv6_arr as $ipv6) {
              if (substr($ipv6, 0, 4 ) === "fe80") {
                continue;
              }
              echo $ipv6."&nbsp;";
            }
            ?>
          </small>
        </div>
      </div>
      <div class="row">
        <div class="col border border-bottom-0" style="padding-top: 20px; padding-bottom: 5px;">
          <h4 style="text-align: center;"><i class="fas fa-download"></i> Data</h4>
        </div>
      </div>
      <div class="row">
        <div class="col border border-bottom-0" style="text-align: center;">
          <?php echo shell_exec('vnstati -s --noheader --noedge -i '.$wan_interface.' -o ./summary.png'); ?>
          <a href="/vnstat">
            <img src="summary.png" class="img-fluid" alt="Responsive image">
          </a>
        </div>
      </div>
      <div class="row">
        <?php
        $leases_file = '/var/lib/misc/dnsmasq.leases';
        if (!file_exists($leases_file)) {
          $leases_file = 'dnsmasq.leases';
        }
        $leases = shell_exec('cat '.$leases_file);
        $leases = (string)trim($leases);
        $leases_arr = explode("\n", $leases);
        ?>
        <div class="col border border-bottom-0" style="padding-top: 20px; padding-bottom: 5px;">
          <h4 style="text-align: center;">DHCP Leases</h4>
        </div>
      </div>
      <div class="row">
        <div class="col border border-top-0" style="padding-top: 5px; padding-bottom: 5px; overflow-x:auto;">
          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th scope="col">Hostname</th>
                <th scope="col">MAC Address</th>
                <th scope="col">IP Address</th>
                <th scope="col">Lease Until</th>
              </tr>
            </thead>
            <tbody>
              <?php
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
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col" style="padding-top: 10px;">
          <p style="text-align: center;"><small>Copyright &copy; 2018 Viraj Chitnis. All rights reserved.</small></p>
        </div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="js/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
  </body>
</html>
