<div>
  <div class="status">
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
      $s_user_id = $this->session->userdata('user_id');
      $s_privilege = $this->session->userdata('privilege');
      $error = null;
      if ($status === 'Running' && !can_admin($s_privilege) && (int)$submission->user_id !== $s_user_id) {
          $error = 'Sorry, you can only view your own source code during a contest.';
          $result = ((int)$submission->result_key === get_result_key('Accepted') ? 'Yes' : 'No');
          $result_class = 'result-' . strtolower($result);
          if ((int)$submission->result_key === get_result_key('Queuing')) {
              $result = $submission->result;
              $result_class = "result{$submission->result_key}";
          }
?>
        <tr class="odd">
          <td><?php echo $submission->id; ?></td>
          <td><a href="user/profile/<?php echo $submission->username; ?>"><?php echo $submission->username; ?></a></td>
          <td><a href="contests/<?php echo $contest->id; ?>/problem/<?php echo $problem->flag; ?>"><?php echo $problem->flag; ?></a></td>
          <td class="<?php echo $result_class; ?>"><?php echo htmlspecialchars($result); ?></td>
          <td>N/A</td>
          <td>N/A</td>
          <td>N/A</td>
          <td>N/A</td>
          <td style="width: 12em"><?php echo $submission->submission_time; ?></td>
        </tr>
<?php
      } else {
          if ($submission->is_shared !== '1' && !can_view_code($s_privilege) && (int)$submission->user_id !== $s_user_id) {
              $error = 'Sorry, this source code is private.';
          }
          $refresh = '';
          if (can_admin($s_privilege) || (int)$submission->result_key === get_result_key('System Error')) {
              $refresh = "<a href=\"problems/resubmit/{$submission->id}\"><img style=\"width: 1em; height: 1em\" src=\"images/refresh.png\" /></a>";
          }
?>
        <tr class="odd">
          <td><?php echo $submission->id; ?></td>
          <td><a href="user/profile/<?php echo $submission->username; ?>"><?php echo $submission->username; ?></a></td>
          <td><a href="contests/<?php echo $contest->id; ?>/problem/<?php echo $problem->flag; ?>"><?php echo $problem->flag; ?></a></td>
          <td class="result<?php echo $submission->result_key; ?>"><?php echo htmlspecialchars($submission->result); ?><?php echo $refresh; ?></td>
          <td><?php echo htmlspecialchars($submission->language); ?></td>
          <td><?php echo $submission->time === null ? 'N/A' : $submission->time . 'MS'; ?></td>
          <td><?php echo $submission->memory === null ? 'N/A' : $submission->memory . 'KB'; ?></td>
          <td><?php echo $submission->length; ?>B</td>
          <td style="width: 12em"><?php echo $submission->submission_time; ?></td>
        </tr>
<?php } ?>
      </tbody>
    </table>
  </div>
  <div class="submission">
<?php if ($error === null && $submission->additional_info !== null) { ?>
    <div class="container info">
      <div class="title">Additional infomation</div>
      <hr />
      <pre class="info"><?php echo $submission->additional_info; ?></pre>
    </div>
<?php } ?>
    <div class="container info">
      <div class="title">Source code</div>
      <hr />
<?php if ($error === null) { ?>
      <pre class="info"><?php echo htmlspecialchars($submission->source_code); ?></pre>
<?php } else { ?>
      <div class="error"><?php echo $error; ?></div>
<?php } ?>
    </div>
  </div>
</div>
