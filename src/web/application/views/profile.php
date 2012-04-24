<div>
  <div>
    <div class="container profile">
      <div>
        <a class="username" href="user/profile/<?php echo $profile->username; ?>"><?php echo $profile->username; ?></a>
        <span class="disabled"><?php if ((int)$profile->enabled === 0) echo '(This user is diabled by admin.)'; ?></span>
      </div>
      <div>
<?php if ($profile->real_name !== null) { ?>
        <span class="real_name"><?php echo htmlspecialchars($profile->real_name); if ($profile->school !== null) echo ','; ?></span>
<?php } ?>
<?php if ($profile->school !== null) { ?>
        <a class="school" href="user/search/:<?php echo rawurlencode($profile->school); ?>"><?php echo htmlspecialchars($profile->school); ?></a>
<?php } ?>
      </div>
      <ul>
        <li><span class="field">Rank: </span><?php echo $rank; ?></li>
        <li><span class="field">Submissions: </span><a href="problems/status/::<?php echo $profile->username; ?>::"><?php echo $profile->submissions; ?></a></li>
        <li><span class="field">Solutions: </span><a href="problems/status/::<?php echo $profile->username; ?>::<?php echo get_result_key('Accepted'); ?>"><?php echo $profile->solutions; ?></a></li>
        <li><span class="field">Success: </span><?php echo sprintf('%.2f', $profile->submissions > 0 ? $profile->solutions * 100.0 / $profile->submissions : 0); ?>%</li>
<?php
      $email = $profile->email;
      if ($email !== null) {
          if ($is_self || (int)$profile->share_email === 1) {
              $email = str_replace('@', '[#at]', htmlspecialchars($email));
              if ((int)$profile->share_email === 0) {
                  $email .= ' (not public)';
              }
          } else {
              $email = '(not public)';
          }
      }
?>
        <li><span class="field">Email: </span><?php echo $email; ?></li>
        <li><span class="field">Member since: </span><?php echo $profile->registration_time; ?></li>
      </ul>
<?php if ($is_self) { ?>
      <div><a href="user/update">Update my profile</a></div>
<?php } ?>
    </div>
    <div class="container dark" style="padding: 1px">
      <div style="padding: 0.25em 0.5em">Solved problem list</div>
      <div class="container solutions" style="margin: 0">
<?php foreach (get_available_sites() as $site) {
          if ($site === 'All') {
              continue;
          }
?>
        <div>
          <a class="site" href="problems/index/<?php echo rawurlencode($site); ?>"><?php echo htmlspecialchars($site); ?></a>
          <table class="data fixed">
            <tbody>
<?php     $count = 0;
          foreach ($solutions as $solution) {
              if ($solution->original_site !== $site) {
                  continue;
              }
              if ($count % 10 === 0) {
                  if ($count !== 0) {
?>
              </tr>
<?php             } ?>
              <tr>
<?php         } ?>
                <td><div><a href="problems/view/<?php echo $solution->problem_id; ?>"><?php echo htmlspecialchars($solution->original_problem_id); ?></a></div></td>
<?php         ++$count;
          }
          if ($count !== 0) {
              while ($count++ % 10 !== 0) {
?>
                <td></td>
<?php         } ?>
              </tr>
<?php     } ?>
            </tbody>
          </table>
        </div>
<?php } ?>
      </div>
    </div>
  </div>
</div>
