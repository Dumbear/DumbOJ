<!--IS OK-->
<?php
      $s_user_id = $this->session->userdata('user_id');
      $s_privilege = $this->session->userdata('privilege');
      $cond_site = isset($conditions['original_site']) ? $conditions['original_site'] : 'All';
      $cond_id = isset($conditions['original_problem_id']) ? $conditions['original_problem_id'] : '';
      $cond_username = isset($conditions['username']) ? $conditions['username'] : '';
      $cond_language = isset($conditions['language_key']) ? $conditions['language_key'] : '';
      $cond_result = isset($conditions['result_key']) ? $conditions['result_key'] : '';
?>
<div>
  <div class="container dark" style="padding: 1px">
    <div style="padding: 0.25em 0.5em">Status from
      <form style="display: inline" id="filter_status" action="" method="post">
        Problem:
        <select id="site">
<?php
      foreach (get_available_sites() as $site) {
          $selected = ($cond_site === $site ? ' selected=selected' : '');
?>
          <option value="<?php echo rawurlencode($site); ?>"<?php echo $selected; ?>><?php echo htmlspecialchars($site); ?></option>
<?php } ?>
        </select>
        -
        <input style="width: 8em" id="id" value="<?php echo htmlspecialchars($cond_id); ?>" />
        &nbsp;&nbsp;Username:
        <input style="width: 8em" id="username" value="<?php echo htmlspecialchars($cond_username); ?>" />
        &nbsp;&nbsp;Language:
        <select id="language">
<?php
      foreach (get_all_languages() as $key => $language) {
          $selected = ($cond_language === (string)$key ? ' selected=selected' : '');
?>
          <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo htmlspecialchars($language); ?></option>
<?php } ?>
        </select>
      &nbsp;&nbsp;Result:
        <select id="result">
<?php
      foreach (get_all_results() as $key => $result) {
          $selected = ($cond_result === (string)$key ? ' selected=selected' : '');
?>
          <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo htmlspecialchars($result); ?></option>
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
      foreach ($submissions as $item) {
          $tr_class = alternator('odd', 'even');
          $user_id = (int)$item->user_id;
          $is_shared = ((int)$item->is_shared === 1 ? true : false);
          $id = $item->id;
          if ($user_id === $s_user_id || can_view_code($s_privilege) || $is_shared) {
              $id = "<a href=\"problems/submission/{$id}\">{$id}</a>";
          }
          $username = $item->username;
          $problem_id = (int)$item->problem_id;
          $original_site = htmlspecialchars($item->original_site);
          $original_problem_id = htmlspecialchars($item->original_problem_id);
          $result = htmlspecialchars($item->result);
          $result_key = (int)$item->result_key;
          $result_class = "result{$result_key}";
          $refresh = '';
          if (can_admin($s_privilege) || $result_key === get_result_key('System Error')) {
              $refresh = "<a href=\"problems/resubmit/{$item->id}\"><img style=\"width: 1em; height: 1em\" src=\"images/refresh.png\" /></a>";
          }
          $language = htmlspecialchars($item->language);
          $time = ($item->time === null ? 'N/A' : $item->time . 'MS');
          $memory = ($item->memory === null ? 'N/A' : $item->memory . 'KB');
          $length = $item->length . 'B';
          $submission_time = ($item->submission_time === null ? 'N/A' : $item->submission_time);
?>
          <tr class="<?php echo $tr_class; ?>">
            <td><?php echo $id; ?></td>
            <td><a href="user/profile/<?php echo $username; ?>"><?php echo $username; ?></a></td>
            <td><a href="problems/view/<?php echo $problem_id; ?>"><?php echo $original_site; ?> - <?php echo $original_problem_id; ?></a></td>
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
        var id = rawurlencode($("#id").val());
        var username = rawurlencode($("#username").val());
        window.location = "problems/status/" + $("#site").val() + ":" + id + ":" + username + ":" + $("#language").val() + ":" + $("#result").val();
        return false;
    });
</script>
