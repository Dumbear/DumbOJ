<!--IS OK-->
<div>
  <div class="contests">
    <div class="op"><a href="contests/add">Add new contest</a>&nbsp;&nbsp;<a href="contests">View current contests</a></div>
    <div class="container dark" style="padding: 1px">
      <div style="padding: 0.25em 0.5em">Past contests</div>
      <div class="container" style="margin: 0">
        <div class="pagination"><?php echo $pagination; ?></div>
        <table class="data">
          <thead>
            <tr>
              <th>Title</th>
              <th style="width: 12em">Start at</th>
              <th style="width: 12em">Duration</th>
              <th style="width: 8em">Type</th>
              <th style="width: 30%">Manager</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($contests as $contest) { ?>
            <tr class="<?php echo alternator('odd', 'even'); ?>">
              <td><a href="contests/<?php echo $contest->id; ?>"><?php echo htmlspecialchars($contest->title); ?></a></td>
              <td><?php echo $contest->start_time; ?></td>
              <td><?php echo date_diff(date_create($contest->start_time), date_create($contest->end_time))->format('%a Days %H:%I:%S'); ?></td>
              <td class="<?php echo $contest->password === null ? 'public' : 'private'; ?>"><?php echo $contest->password === null ? 'Public' : 'Private'; ?></td>
              <td><a href="user/profile/<?php echo $contest->username; ?>"><?php echo $contest->username; ?></a></td>
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
