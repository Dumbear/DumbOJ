<div class="contest">
  <div class="container dark" style="padding: 1px">
    <div style="padding: 0.25em 0.5em">Standings</div>
    <div class="container" style="margin: 0">
      <div class="pagination"><?php echo $pagination; ?></div>
      <div style="min-width: 100%; overflow: auto">
        <table class="data fixed standings">
          <thead>
            <tr>
              <th style="width: 5em">Rank</th>
              <th style="width: 10em">User</th>
              <th style="width: 4em">Yes</th>
              <th style="width: 6em">Penalty</th>
<?php
      $flag_map = array();
      foreach ($problems as $item) {
          $flag_map[$item->id] = $item->flag;
?>
              <th style="width: 6em"><a href="contests/<?php echo $contest->id; ?>/problem/<?php echo $item->flag; ?>"><?php echo $item->flag; ?></a></th>
<?php } ?>
            </tr>
          </thead>
          <tbody>
<?php
      foreach ($contestants as $rank => $contestant) {
          $penalty = (int)$contestant->penalty;
          $penalty = sprintf('%d:%02d:%02d', $penalty / 3600, $penalty / 60 % 60, $penalty % 60);
          $json = json_decode($contestant->json, true);
          $info = array();
          $info_class = array();
          foreach ($problems as $item) {
              $info[$item->flag] = array('time' => null, 'count' => 0);
              $info_class[$item->flag] = '';
          }
          ksort($json);
          foreach ($json as $item) {
              $item['k'] = $flag_map[$item['k']];
              if ((int)$item['r'] === get_result_key('Queuing')) {
                  if ($info[$item['k']]['time'] === null) {
                      $info[$item['k']]['time'] = -1;
                      $info_class[$item['k']] = 'info_pending';
                  }
              } else if ((int)$item['r'] === get_result_key('Accepted')) {
                  if ($info[$item['k']]['time'] === null) {
                      $info[$item['k']]['time'] = (int)$item['t'];
                      $info_class[$item['k']] = 'info_yes';
                  }
              } else if ($info[$item['k']]['time'] === null) {
                  ++$info[$item['k']]['count'];
                  $info_class[$item['k']] = 'info_no';
              }
          }
          foreach ($problems as $item) {
              $time = '&nbsp;';
              if ($info[$item->flag]['time'] === -1) {
                  $time = 'Pending';
              } else if ($info[$item->flag]['time'] !== null) {
                  $time = (int)$info[$item->flag]['time'];
                  $time = sprintf('%d:%02d:%02d', $time / 3600, $time / 60 % 60, $time % 60);
              }
              $count = '&nbsp;';
              if ($info[$item->flag]['count'] !== 0) {
                  $count = -$info[$item->flag]['count'];
              }
              $info[$item->flag] = "{$time}<br />{$count}";
          }
?>
            <tr class="<?php echo alternator('odd', 'even'); ?>">
              <td><?php echo $rank + $offset + 1; ?></td>
              <td><div><a href="user/profile/<?php echo $contestant->username; ?>"><?php echo $contestant->username; ?></a></div></td>
              <td><a href="contests/<?php echo $contest->id; ?>/status/:<?php echo $contestant->username; ?>::"><?php echo $contestant->solutions; ?></a></td>
              <td style="padding-left: 0; padding-right: 0"><?php echo $penalty; ?></td>
<?php     foreach ($problems as $item) { ?>
              <td class="<?php echo $info_class[$item->flag]; ?>" style="padding-left: 0; padding-right: 0"><?php echo $info[$item->flag]; ?></td>
<?php     } ?>
            </tr>
<?php } ?>
          </tbody>
        </table>
      </div>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
