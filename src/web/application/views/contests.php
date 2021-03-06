<div>
  <div class="contests">
<?php $now = new DateTime(); ?>
    <div class="op"><a href="contests/add">Add new contest</a>&nbsp;&nbsp;<a href="contests/past">View past contests</a></div>
    <div class="container dark" style="padding: 1px">
      <div style="padding: 0.25em 0.5em">Current contests</div>
      <div class="container" style="margin: 0">
<?php if (count($current_contests) === 0) { ?>
        <div class="error">There are no contests running right now.</div>
<?php } else { ?>
        <table class="data fixed current">
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
<?php     foreach ($current_contests as $contest) {
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
<?php     } ?>
          </tbody>
        </table>
<?php } ?>
      </div>
    </div>
<?php alternator(); ?>
    <div class="container dark" style="padding: 1px; margin-top: 1.5em">
      <div style="padding: 0.25em 0.5em">Upcoming contests</div>
      <div class="container" style="margin: 0">
<?php if (count($upcoming_contests) === 0) { ?>
        <div class="error">There are no upcoming contests.</div>
<?php } else { ?>
        <table class="data fixed upcoming">
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
<?php     foreach ($upcoming_contests as $contest) {
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
<?php     } ?>
          </tbody>
        </table>
<?php } ?>
      </div>
    </div>
    <div class="op"><a href="contests/add">Add new contest</a>&nbsp;&nbsp;<a href="contests/past">View past contests</a></div>
  </div>
</div>
