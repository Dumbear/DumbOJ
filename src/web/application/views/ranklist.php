<div>
  <div>
    <div class="container dark" style="padding: 1px">
      <div style="padding: 0.25em 0.5em">User ranklist</div>
      <div class="container" style="margin: 0">
        <div class="pagination"><?php echo $pagination; ?></div>
        <table class="data fixed">
          <thead>
            <tr>
              <th style="width: 6em">Rank</th>
              <th>Username</th>
              <th>Real Name</th>
              <th>School</th>
              <th style="width: 8em">Submissions</th>
              <th style="width: 8em">Solutions</th>
              <th style="width: 6em">Success</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($ranklist as $rank => $item) { ?>
            <tr class="<?php echo alternator('odd', 'even'); ?>">
              <td><?php echo $rank + $offset + 1; ?></td>
              <td><div><a href="user/profile/<?php echo $item->username; ?>"><?php echo $item->username; ?></a></div></td>
<?php     if ($item->real_name === null) { ?>
              <td></td>
<?php     } else { ?>
              <td><div><?php echo htmlspecialchars($item->real_name); ?></div></td>
<?php     } ?>
<?php     if ($item->school === null) { ?>
              <td></td>
<?php     } else { ?>
              <td><div><a href="user/search/:<?php echo rawurlencode($item->school); ?>"><?php echo htmlspecialchars($item->school); ?></a></div></td>
<?php     } ?>
              <td><a href="problems/status/::<?php echo $item->username; ?>::"><?php echo $item->submissions; ?></a></td>
              <td><a href="problems/status/::<?php echo $item->username; ?>::<?php echo get_result_key('Accepted'); ?>"><?php echo $item->solutions; ?></a></td>
              <td style="text-align: right"><?php echo sprintf('%.2f', $item->submissions > 0 ? $item->solutions * 100.0 / $item->submissions : 0); ?>%</td>
            </tr>
<?php } ?>
          </tbody>
        </table>
        <div class="pagination"><?php echo $pagination; ?></div>
      </div>
    </div>
  </div>
</div>
