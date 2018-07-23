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

    <?php
    $testipv4socket = @fsockopen($connectivity_test_host, 80);
    $connected = false;
    if ($testipv4socket) {
      $connected = true;
    }
    fclose($testipv4socket);

    $external_ipv4 = "";
    $external_ipv6 = "";
    $isp_name = "";

    if ($connected) {
      $external_ipv4 = shell_exec("dig +short myip.opendns.com @resolver1.opendns.com");
      $external_ipv4 = (string)trim($external_ipv4);
      $external_ipv6 = shell_exec("curl https://ipv6.icanhazip.com/");
      $external_ipv6 = (string)trim($external_ipv6);

      if (file_exists("isp_details.txt")) {
        $isp_details = shell_exec("cat isp_details.txt");
        $isp_details = (string)trim($isp_details);
        $isp_details_arr = explode("\n", $isp_details);

        if ($external_ipv4 !== $isp_details_arr[0]) {
          $isp_name = shell_exec("curl https://ipapi.co/".$external_ipv4."/org/");
          $isp_name = (string)trim($isp_name);

          $isp_details_file = 'isp_details.txt';
          $handle = fopen($isp_details_file, 'w') or die('Cannot open file: '.$isp_details_file);
          $data = $external_ipv4."\n".$isp_name;
          fwrite($handle, $data);
        } else {
          $isp_name = $isp_details_arr[1];
        }
      } else {
        $isp_name = shell_exec("curl https://ipapi.co/".$external_ipv4."/org/");
        $isp_name = (string)trim($isp_name);

        $isp_details_file = 'isp_details.txt';
        $handle = fopen($isp_details_file, 'w') or die('Cannot open file: '.$isp_details_file);
        $data = $external_ipv4."\n".$isp_name;
        fwrite($handle, $data);
      }
    }
    ?>

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
        <div class="col border border-bottom-0" id="systime">
          <?php include "api/getsystime.php" ?>
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
        <div class="col border border-bottom-0" id="sysload">
          <?php include "api/getloadavg.php" ?>
        </div>
      </div>
      <div class="row">
        <div class="col-3 border border-right-0 border-bottom-0">
          <strong><i class="fas fa-memory"></i> Memory Usage</strong>
        </div>
        <div class="col border border-bottom-0" style="padding-top: 5px; padding-bottom: 5px;" id="sysmem">
          <?php include "api/getsysmem.php" ?>
        </div>
      </div>
      <div class="row">
        <div class="col-3 border border-right-0 border-bottom-0">
          <strong><i class="fas fa-hdd"></i> Disk Usage</strong>
        </div>
        <div class="col border border-bottom-0" style="padding-top: 5px; padding-bottom: 5px;" id="sysdisk">
          <?php include "api/getsysdisk.php" ?>
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
          $lan_ipv6 = shell_exec("ip addr show dev ".$wan_interface." | grep -w inet6 | awk '{ print $2}'");
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
          <small><strong>ISP:</strong>&nbsp;<?php echo $isp_name ?></small><br />
          <small><strong>Public IPv4:</strong>&nbsp;<?php echo $external_ipv4 ?></small><br />
          <small><strong>Public IPv6:</strong>&nbsp;<?php echo $external_ipv6 ?></small><br />
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
          $lan_ipv6 = shell_exec("ip addr show dev ".$lan_interface." | grep -w inet6 | awk '{ print $2}'");
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
                } else if (strpos(strtolower($lease_arr[3]), 'linksys') !== false) {
                  $display_symbol = "<i class='fas fa-wifi'></i>";
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
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {

      function reloadThings() {
        getsystime();
        getsysmem();
        getsysdisk();
        getloadavg();
      }

      function getloadavg() {
        $.ajax({
          url: "api/getloadavg.php",
          success: function(result) {
            $("#sysload").html(result);
          }
        });
      }

      function getsysmem() {
        $.ajax({
          url: "api/getsysmem.php",
          success: function(result) {
            $("#sysmem").html(result);
          }
        });
      }

      function getsysdisk() {
        $.ajax({
          url: "api/getsysdisk.php",
          success: function(result) {
            $("#sysdisk").html(result);
          }
        });
      }

      function getsystime() {
        $.ajax({
          url: "api/getsystime.php",
          success: function(result) {
            $("#systime").html(result);
          }
        });
      }

      setInterval(reloadThings, 5000);

    });
    </script>
  </body>
</html>
