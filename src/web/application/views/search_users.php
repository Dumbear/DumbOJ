<div>
  <div class="container dark" style="padding: 1px">
    <div style="padding: 0.25em 0.5em">Users from
      <form class="filter_users" style="display: inline" action="" method="post">
        Name:
<?php $cond_name = (isset($conditions['name']) ? $conditions['name'] : ''); ?>
        <input style="width: 8em" name="name" value="<?php echo htmlspecialchars($cond_name); ?>" />
        &nbsp;&nbsp;
        School:
<?php $cond_school = (isset($conditions['school']) ? $conditions['school'] : ''); ?>
        <input style="width: 12em" name="school" value="<?php echo htmlspecialchars($cond_school); ?>" />
        <input type="submit" value="Filter" />
      </form>
    </div>
    <div class="container" style="margin: 0">
<?php if (count($users) >= 100) { ?>
      <div class="error">More than 100 users matched, only shows the first 100 users.</div>
<?php } ?>
      <table class="data fixed">
        <thead>
          <tr>
            <th>Username</th>
            <th>Real Name</th>
            <th>School</th>
            <th>Email</th>
            <th style="width: 8em">Submissions</th>
            <th style="width: 8em">Solutions</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($users as $item) { ?>
          <tr class="<?php echo alternator('odd', 'even'); ?>">
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
<?php     if ($item->email === null) { ?>
            <td></td>
<?php     } else if ((int)$item->share_email === 0) { ?>
            <td>(not public)</td>
<?php     } else { ?>
            <td><div><?php echo htmlspecialchars(str_replace('@', '[#at]', $item->email)); ?></div></td>
<?php     } ?>
            <td><a href="problems/status/::<?php echo $item->username; ?>::"><?php echo $item->submissions; ?></a></td>
            <td><a href="problems/status/::<?php echo $item->username; ?>::<?php echo get_result_key('Accepted'); ?>"><?php echo $item->solutions; ?></a></td>
          </tr>
<?php } ?>
        </tbody>
      </table>
<?php if (count($users) >= 100) { ?>
      <div class="error">More than 100 users matched, only shows the first 100 users.</div>
<?php } ?>
    </div>
  </div>
</div>
<script type="text/javascript">
    $("form.filter_users").submit(function() {
        var name = rawurlencode($("[name=name]", $(this)).val());
        var school = rawurlencode($("[name=school]", $(this)).val());
        window.location = $("base").attr("href") + "user/search/" + name + ":" + school;
        return false;
    });
</script>
