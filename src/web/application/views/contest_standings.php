<div class="contest">
  <div class="container dark" style="padding: 1px">
    <div style="padding: 0.25em 0.5em">Standings</div>
    <div class="container" style="margin: 0">
      <div class="pagination"><?php echo $pagination; ?></div>
      <table class="data standings">
        <thead>
          <tr>
            <th style="width: 4em">Rank</th>
            <th style="">User</th>
            <th style="width: 2em">Yes</th>
            <th style="width: 8em">Penalty</th>
<?php
      $flag_map = array();
      foreach ($problems as $item) {
          $flag = $item->flag;
          $flag_map[$item->id] = $flag;
?>
            <th><a href="contests/<?php echo $contest->id; ?>/problem/<?php echo $flag; ?>"><?php echo $flag; ?></a>(<?php echo $item->solutions; ?>/<?php echo $item->submissions; ?>)</th>
<?php } ?>
          </tr>
        </thead>
        <tbody>
<?php
      foreach ($contestants as $rank => $item) {
          $tr_class = alternator('odd', 'even');
          $rank += $offset + 1;
          $username = $item->username;
          $solutions = $item->solutions;
          $penalty = (int)$item->penalty;
          $penalty = sprintf('%d:%02d:%02d', $penalty / 3600, $penalty / 60 % 60, $penalty % 60);
          $json = json_decode($item->json, true);
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
          <tr class="<?php echo $tr_class; ?>">
            <td><?php echo $rank; ?></td>
            <td><a href="user/profile/<?php echo $username; ?>"><?php echo $username; ?></a></td>
            <td><a href="contests/<?php echo $contest->id; ?>/status/:<?php echo $username; ?>::"><?php echo $solutions; ?></a></td>
            <td><?php echo $penalty; ?></td>
<?php     foreach ($problems as $item) { ?>
            <td class="<?php echo $info_class[$item->flag]; ?>"><?php echo $info[$item->flag]; ?></td>
<?php     } ?>
          </tr>
<?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
