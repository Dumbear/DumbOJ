<!--IS OK-->
<div>
  <div>
    <div class="container dark" style="padding: 1px">
      <div style="padding: 0.25em 0.5em">User ranklist</div>
      <div class="container" style="margin: 0">
        <div class="pagination"><?php echo $pagination; ?></div>
        <table class="data">
          <thead>
            <tr>
              <th style="width: 10%">Rank</th>
              <th style="width: 20%">Username</th>
              <th style="width: 20%">Real Name</th>
              <th style="width: 20%">School</th>
              <th style="width: 10%">Submissions</th>
              <th style="width: 10%">Solutions</th>
              <th style="width: 10%">Success</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($ranklist as $rank => $item) { ?>
            <tr class="<?php echo alternator('odd', 'even'); ?>">
              <td><?php echo $offset + $rank + 1; ?></td>
              <td><a href="user/profile/<?php echo $item->username; ?>"><?php echo $item->username; ?></a></td>
              <td><?php echo htmlspecialchars($item->real_name); ?></td>
              <td><a href="user/search/:<?php echo rawurlencode($item->school); ?>"><?php echo htmlspecialchars($item->school); ?></a></td>
              <td><a href="problems/status/::<?php echo $item->username; ?>::"><?php echo $item->submissions; ?></a></td>
              <td><a href="problems/status/::<?php echo $item->username; ?>::<?php echo get_result_key('Accepted'); ?>"><?php echo $item->solutions; ?></a></td>
              <td><?php echo sprintf('%.2f', $item->submissions > 0 ? $item->solutions * 100.0 / $item->submissions : 0); ?>%</td>
            </tr>
<?php } ?>
          </tbody>
        </table>
        <div class="pagination"><?php echo $pagination; ?></div>
      </div>
    </div>
  </div>
</div>
