<!--IS OK-->
<div>
  <div class="contests">
<?php $current_time = date_create(); ?>
    <div class="op"><a href="contests/add">Add new contest</a>&nbsp;&nbsp;<a href="contests/past">View past contests</a></div>
    <div class="container dark" style="padding: 1px">
      <div style="padding: 0.25em 0.5em">Current contests</div>
      <div class="container" style="margin: 0">
        <table class="data fixed current">
          <thead>
            <tr>
              <th>Title</th>
              <th style="width: 14em">Start at</th>
              <th style="width: 12em">Duration</th>
              <th style="width: 8em">Type</th>
              <th style="width: 30%">Manager</th>
            </tr>
          </thead>
          <tbody>
<?php $count = 0;
      foreach ($contests as $contest) {
          if (date_create($contest->start_time) <= $current_time) {
              ++$count;
?>
            <tr class="<?php echo alternator('odd', 'even'); ?>">
              <td><div><a href="contests/<?php echo $contest->id; ?>"><?php echo htmlspecialchars($contest->title); ?></a></div></td>
              <td><?php echo $contest->start_time; ?></td>
              <td><?php echo date_diff(date_create($contest->start_time), date_create($contest->end_time))->format('%a Days %H:%I:%S'); ?></td>
              <td class="<?php echo $contest->password === null ? 'public' : 'private'; ?>"><?php echo $contest->password === null ? 'Public' : 'Private'; ?></td>
              <td><div><a href="user/profile/<?php echo $contest->username; ?>"><?php echo $contest->username; ?></a></div></td>
            </tr>
<?php     }
      }
?>
          </tbody>
        </table>
<?php if ($count === 0) { ?>
        <div class="error">There is no contests running right now.</div>
<?php } ?>
      </div>
    </div>
    <div class="container dark" style="padding: 1px; margin-top: 1.5em">
      <div style="padding: 0.25em 0.5em">Upcoming contests</div>
      <div class="container" style="margin: 0">
        <table class="data fixed upcoming">
          <thead>
            <tr>
              <th>Title</th>
              <th style="width: 14em">Start at</th>
              <th style="width: 12em">Duration</th>
              <th style="width: 8em">Type</th>
              <th style="width: 30%">Manager</th>
            </tr>
          </thead>
          <tbody>
<?php $count = 0;
      foreach ($contests as $contest) {
          if (date_create($contest->start_time) > $current_time) {
              ++$count;
?>
            <tr class="<?php echo alternator('odd', 'even'); ?>">
              <td><div><a href="contests/<?php echo $contest->id; ?>"><?php echo htmlspecialchars($contest->title); ?></a></div></td>
              <td><?php echo $contest->start_time; ?></td>
              <td><?php echo date_diff(date_create($contest->start_time), date_create($contest->end_time))->format('%a Days %H:%I:%S'); ?></td>
              <td class="<?php echo $contest->password === null ? 'public' : 'private'; ?>"><?php echo $contest->password === null ? 'Public' : 'Private'; ?></td>
              <td><div><a href="user/profile/<?php echo $contest->username; ?>"><?php echo $contest->username; ?></a></div></td>
            </tr>
<?php     }
      }
?>
          </tbody>
        </table>
<?php if ($count === 0) { ?>
        <div class="error">There is no upcoming contests.</div>
<?php } ?>
      </div>
    </div>
    <div class="op"><a href="contests/add">Add new contest</a>&nbsp;&nbsp;<a href="contests/past">View past contests</a></div>
  </div>
</div>
