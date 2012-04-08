<!--IS OK-->
<?php
      $s_user_id = $this->session->userdata('user_id');
      $s_privilege = $this->session->userdata('privilege');
?>
<div>
  <div class="container dark" style="padding: 1px">
    <div style="padding: 0.25em 0.5em">Status from
      <form style="display: inline" id="filter_status" action="" method="post">
        Problem:
        <select id="id">
          <option value="">All</option>
<?php
      $flag_map = array();
      foreach ($problems as $item) {
          $id = $item->id;
          $flag = $item->flag;
          $selected = '';
          if (isset($conditions['problem_id']) && $conditions['problem_id'] === $id) {
              $selected = ' selected="selected"';
          }
          $title = htmlspecialchars($item->title);
          $flag_map[$id] = $flag;
?>
          <option value="<?php echo $id; ?>"<?php echo $selected; ?>><?php echo $flag; ?> - <?php echo $title; ?></option>
<?php } ?>
        </select>
        &nbsp;&nbsp;Username:
<?php
      $username = '';
      if (isset($conditions['username'])) {
          $username = htmlspecialchars($conditions['username']);
      }
?>
        <input style="width: 8em" id="username" value="<?php echo $username; ?>" />
        &nbsp;&nbsp;Language:
        <select id="language">
<?php
      foreach (get_all_languages() as $key => $language) {
          $selected = '';
          if (isset($conditions['language_key']) && $conditions['language_key'] === (string)$key) {
              $selected = ' selected="selected"';
          }
          $language = htmlspecialchars($language);
?>
          <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $language; ?></option>
<?php } ?>
        </select>
        &nbsp;&nbsp;Result:
        <select id="result">
<?php
      foreach (get_all_results() as $key => $result) {
          $selected = '';
          if (isset($conditions['result_key']) && $conditions['result_key'] === (string)$key) {
              $selected = ' selected="selected"';
          }
          $result = htmlspecialchars($result);
?>
          <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $result; ?></option>
<?php } ?>
        </select>
        <input type="submit" value="Filter" />
      </form>
    </div>
    <div class="container status" style="margin: 0">
      <div class="pagination"><?php echo $pagination; ?></div>
      <table class="data status">
        <thead>
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Problem</th>
            <th>Result</th>
            <th>Language</th>
            <th>Time</th>
            <th>Memory</th>
            <th>Length</th>
            <th>Submit at</th>
          </tr>
        </thead>
        <tbody>
<?php
      $contest_id = $contest->id;
      foreach ($submissions as $item) {
          $tr_class = alternator('odd', 'even');
          $user_id = (int)$item->user_id;
          $is_shared = ((int)$item->is_shared === 1 ? true : false);
          $id = $item->id;
          if ($user_id === $s_user_id || can_view_code($s_privilege) || ($status === 'Ended' && $is_shared)) {
              $id = "<a href=\"contests/{$contest_id}/submission/{$id}\">{$id}</a>";
          }
          $username = $item->username;
          $flag = $flag_map[$item->problem_id];
          $result = htmlspecialchars($item->result);
          $result_key = (int)$item->result_key;
          $result_class = "result{$result_key}";
          if ($user_id !== $s_user_id && !can_admin($s_privilege) && $status === 'Running') {
              if ($result_key === get_result_key('Accepted')) {
                  $result = 'Yes';
                  $result_class = 'result-yes';
              } else if ($result_key !== get_result_key('Queuing')) {
                  $result = 'No';
                  $result_class = 'result-no';
              }
          }
          $refresh = '';
          if (can_admin($s_privilege) || ($result !== 'No' && $result_key === get_result_key('System Error'))) {
              $refresh = "<a href=\"problems/resubmit/{$item->id}\"><img style=\"width: 1em; height: 1em\" src=\"images/refresh.png\" /></a>";
          }
          $language = htmlspecialchars($item->language);
          $time = ($item->time === null ? 'N/A' : $item->time . 'MS');
          $memory = ($item->memory === null ? 'N/A' : $item->memory . 'KB');
          $length = $item->length . 'B';
          if ($user_id !== $s_user_id && !can_admin($s_privilege) && $status === 'Running') {
              $language = 'N/A';
              $time = 'N/A';
              $memory = 'N/A';
              $length = 'N/A';
          }
          $submission_time = $item->submission_time;
          if ($submission_time === null) {
              $submission_time = 'N/A';
          }
?>
          <tr class="<?php echo $tr_class; ?>">
            <td><?php echo $id; ?></td>
            <td><a href="user/profile/<?php echo $username; ?>"><?php echo $username; ?></a></td>
            <td><a href="contests/<?php echo $contest_id; ?>/problem/<?php echo $flag; ?>"><?php echo $flag; ?></a></td>
            <td class="<?php echo $result_class; ?>"><?php echo $result; ?><?php echo $refresh; ?></td>
            <td><?php echo $language; ?></td>
            <td><?php echo $time; ?></td>
            <td><?php echo $memory; ?></td>
            <td><?php echo $length; ?></td>
            <td style="width: 12em"><?php echo $submission_time; ?></td>
          </tr>
<?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $("#filter_status").submit(function() {
        var username = rawurlencode($("#username").val());
        window.location = "contests/<?php echo $contest->id; ?>/status/" + $("#id").val() + ":" + username + ":" + $("#language").val() + ":" + $("#result").val();
        return false;
    });
</script>
