<?php
$disk_total = disk_total_space("/");
$disk_free = disk_free_space("/");
$disk_used = $disk_total - $disk_free;
echo number_format((float)(((($disk_used)/1024)/1024)/1024), 2, '.', '')."/".number_format((float)((($disk_total/1024)/1024)/1024), 2, '.', '')." GB";
$disk_percentage = ($disk_used/$disk_total)*100;
$formatted_disk_percent = number_format((float)$disk_percentage, 2, '.', '');
$disk_progress_color = "bg-success";
if ($formatted_disk_percent > 50) {
  $disk_progress_color = "bg-warning";
}
if ($formatted_disk_percent > 90) {
  $disk_progress_color = "bg-danger";
}
?>
<div class="progress" style="height: 24px;">
  <div class="progress-bar <?php echo $disk_progress_color ?>" role="progressbar" style="width: <?php echo $formatted_disk_percent ?>%;" aria-valuenow="<?php echo $formatted_disk_percent ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $formatted_disk_percent ?>%</div>
</div>
