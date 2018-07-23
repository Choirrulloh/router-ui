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
$memory_progress_color = "bg-success";
if ($memory_usage_percent > 50) {
  $memory_progress_color = "bg-warning";
}
if ($memory_usage_percent > 90) {
  $memory_progress_color = "bg-danger";
}
?>
<?php echo $memory_used_mb."/".$memory_total_mb." MB" ?>
<div class="progress" style="height: 24px;">
  <div class="progress-bar <?php echo $memory_progress_color ?>" role="progressbar" style="width: <?php echo $memory_usage_percent ?>%;" aria-valuenow="<?php echo $memory_usage_percent ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $memory_usage_percent ?>%</div>
</div>
