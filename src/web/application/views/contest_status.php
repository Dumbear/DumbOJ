<div>
  <div class="container dark" style="padding: 1px">
    <div style="padding: 0.25em 0.5em">Status from
      <form class="filter_status" style="display: inline" action="" method="post">
        Problem:
        <select name="id">
          <option value="">All</option>
<?php
      $flag_map = array();
      foreach ($problems as $item) {
          $selected = '';
          if (isset($conditions['problem_id']) && $conditions['problem_id'] === $item->id) {
              $selected = ' selected="selected"';
          }
          $flag_map[$item->id] = $item->flag;
?>
          <option value="<?php echo $item->flag; ?>"<?php echo $selected; ?>><?php echo $item->flag; ?> - <?php echo htmlspecialchars($item->title); ?></option>
<?php } ?>
        </select>
        &nbsp;&nbsp;Username:
        <input style="width: 8em" name="username" value="<?php echo isset($conditions['username']) ? htmlspecialchars($conditions['username']) : ''; ?>" />
        &nbsp;&nbsp;Language:
        <select name="language">
<?php
      foreach (get_all_languages() as $key => $language) {
          $selected = '';
          if (isset($conditions['language_key']) && $conditions['language_key'] === (string)$key) {
              $selected = ' selected="selected"';
          }
?>
          <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo htmlspecialchars($language); ?></option>
<?php } ?>
        </select>
        &nbsp;&nbsp;Result:
        <select name="result"<?php if ($status === 'Running') echo ' disabled="disabled"'; ?>>
<?php
      foreach (get_all_results() as $key => $result) {
          $selected = '';
          if (isset($conditions['result_key']) && $conditions['result_key'] === (string)$key) {
              $selected = ' selected="selected"';
          }
?>
          <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo htmlspecialchars($result); ?></option>
<?php } ?>
        </select>
        <input type="submit" value="Filter" />
      </form>
    </div>
    <div class="container status" style="margin: 0">
      <div class="pagination"><?php echo $pagination; ?></div>
      <div style="min-width: 100%; overflow: auto">
        <table class="data status">
          <thead>
            <tr>
              <th style="width: 6em">ID</th>
              <th>User</th>
              <th style="width: 6em">Problem</th>
              <th>Result</th>
              <th>Language</th>
              <th style="width: 8em">Time</th>
              <th style="width: 8em">Memory</th>
              <th style="width: 8em">Length</th>
              <th style="width: 14em">Submit at</th>
            </tr>
          </thead>
          <tbody>
<?php
      $s_user_id = $this->session->userdata('user_id');
      $s_privilege = $this->session->userdata('privilege');
      foreach ($submissions as $item) {
          if ($status === 'Running' && !can_admin($s_privilege) && (int)$item->user_id !== $s_user_id) {
              $result = ((int)$item->result_key === get_result_key('Accepted') ? 'Yes' : 'No');
              $result_class = 'result-' . strtolower($result);
              if ((int)$item->result_key === get_result_key('Queuing')) {
                  $result = $item->result;
                  $result_class = "result{$item->result_key}";
              }
?>
            <tr class="<?php echo alternator('odd', 'even'); ?>">
<?php         if (can_view_code($s_privilege)) { ?>
              <td><a href="contests/<?php echo $contest->id; ?>/submission/<?php echo $item->id; ?>"><?php echo $item->id; ?></a></td>
<?php         } else { ?>
              <td><?php echo $item->id; ?></td>
<?php         } ?>
              <td><a href="user/profile/<?php echo $item->username; ?>"><?php echo $item->username; ?></a></td>
              <td><a href="contests/<?php echo $contest->id; ?>/problem/<?php echo $flag_map[$item->problem_id]; ?>"><?php echo $flag_map[$item->problem_id]; ?></a></td>
              <td class="<?php echo $result_class; ?>"><?php echo $result; ?></td>
              <td>N/A</td>
              <td>N/A</td>
              <td>N/A</td>
              <td>N/A</td>
              <td><?php echo $item->submission_time; ?></td>
            </tr>
<?php
          } else {
              $refresh = '';
              if (can_admin($s_privilege) || (int)$item->result_key === get_result_key('System Error')) {
                  $refresh = "<a href=\"problems/resubmit/{$item->id}\"><img style=\"width: 1em; height: 1em\" src=\"images/refresh.png\" /></a>";
              }
?>
            <tr class="<?php echo alternator('odd', 'even'); ?>">
<?php         if ($item->is_shared === '1' || can_view_code($s_privilege) || (int)$item->user_id === $s_user_id) { ?>
              <td><a href="contests/<?php echo $contest->id; ?>/submission/<?php echo $item->id; ?>"><?php echo $item->id; ?></a></td>
<?php         } else { ?>
              <td><?php echo $item->id; ?></td>
<?php         } ?>
              <td><a href="user/profile/<?php echo $item->username; ?>"><?php echo $item->username; ?></a></td>
              <td><a href="contests/<?php echo $contest->id; ?>/problem/<?php echo $flag_map[$item->problem_id]; ?>"><?php echo $flag_map[$item->problem_id]; ?></a></td>
              <td class="result<?php echo $item->result_key; ?>"><?php echo $item->result; ?><?php echo $refresh; ?></td>
              <td><?php echo htmlspecialchars($item->language); ?></td>
              <td><?php echo $item->time === null ? 'N/A' : "{$item->time}MS"; ?></td>
              <td><?php echo $item->memory === null ? 'N/A' : "{$item->memory}KB"; ?></td>
              <td><?php echo $item->length; ?>B</td>
              <td><?php echo $item->submission_time; ?></td>
            </tr>
<?php     } ?>
<?php } ?>
          </tbody>
        </table>
      </div>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $("form.filter_status").submit(function() {
        var id = $("[name=id]", $(this)).val();
        var username = rawurlencode($("[name=username]", $(this)).val());
        var language = $("[name=language]", $(this)).val();
        var result = $("[name=result]", $(this)).val();
        window.location = $("base").attr("href") + "contests/<?php echo $contest->id; ?>/status/" + id + ":" + username + ":" + language + ":" + result;
        return false;
    });
</script>
