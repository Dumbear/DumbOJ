<div class="contest">
  <div class="title"><?php echo htmlspecialchars($contest->title); ?></div>
</div>
<div class="submenu">
  <ul class="menu">
    <li><a href="contests/<?php echo $contest->id; ?>"<?php if ($module === 'Overview') echo ' class="current"'; ?>>Overview</a></li>
    <li><a href="contests/<?php echo $contest->id; ?>/problem"<?php if ($module === 'Problems') echo ' class="current"'; ?>>Problems</a></li>
    <li><a href="contests/<?php echo $contest->id; ?>/submit"<?php if ($module === 'Submit') echo ' class="current"'; ?>>Submit</a></li>
    <li><a href="contests/<?php echo $contest->id; ?>/status"<?php if ($module === 'Status') echo ' class="current"'; ?>>Status</a></li>
    <li><a href="contests/<?php echo $contest->id; ?>/standings"<?php if ($module === 'Standings') echo ' class="current"'; ?>>Standings</a></li>
  </ul>
  <div style="clear: both"></div>
</div>
<?php echo $content; ?>
