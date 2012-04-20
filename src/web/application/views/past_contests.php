<div>
  <div class="contests">
    <div class="op"><a href="contests/add">Add new contest</a>&nbsp;&nbsp;<a href="contests">View current contests</a></div>
    <div class="container dark" style="padding: 1px">
      <div style="padding: 0.25em 0.5em">Past contests</div>
      <div class="container" style="margin: 0">
        <div class="pagination"><?php echo $pagination; ?></div>
        <table class="data fixed">
          <thead>
            <tr>
              <th>Title</th>
              <th style="width: 14em">Start at</th>
              <th style="width: 12em">Duration</th>
              <th style="width: 8em">Type</th>
              <th>Manager</th>
            </tr>
          </thead>
          <tbody>
<?php
      foreach ($contests as $contest) {
          $start_time = new DateTime($contest->start_time);
          $end_time = new DateTime($contest->end_time);
          $type = ($contest->password === null ? 'Public' : 'Private');
?>
            <tr class="<?php echo alternator('odd', 'even'); ?>">
              <td><div><a href="contests/<?php echo $contest->id; ?>"><?php echo htmlspecialchars($contest->title); ?></a></div></td>
              <td><?php echo $contest->start_time; ?></td>
              <td><?php echo $start_time->diff($end_time)->format('%a Days %H:%I:%S'); ?></td>
              <td class="<?php echo strtolower($type) ?>"><?php echo $type; ?></td>
              <td><div><a href="user/profile/<?php echo $contest->username; ?>"><?php echo $contest->username; ?></a></div></td>
            </tr>
<?php } ?>
          </tbody>
        </table>
        <div class="pagination"><?php echo $pagination; ?></div>
      </div>
    </div>
    <div class="op"><a href="contests/add">Add new contest</a>&nbsp;&nbsp;<a href="contests">View current contests</a></div>
  </div>
</div>
