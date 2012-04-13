<div>
  <div class="container dark" style="padding: 1px">
    <div style="padding: 0.25em 0.5em">Users from
      <form style="display: inline" id="filter_users" action="" method="post">
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
      <table class="data">
        <thead>
          <tr>
            <th>Username</th>
            <th>Real Name</th>
            <th>School</th>
            <th>Email</th>
            <th>Submissions</th>
            <th>Solutions</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($users as $item) { ?>
          <tr class="<?php echo alternator('odd', 'even'); ?>">
            <td><a href="user/profile/<?php echo $item->username; ?>"><?php echo $item->username; ?></a></td>
<?php     if ($item->real_name === null) { ?>
            <td>N/A</td>
<?php     } else { ?>
            <td><?php echo htmlspecialchars($item->real_name); ?></td>
<?php     } ?>
<?php     if ($item->school === null) { ?>
            <td>N/A</td>
<?php     } else { ?>
            <td><a href="user/search/:<?php echo rawurlencode($item->school); ?>"><?php echo htmlspecialchars($item->school); ?></a></td>
<?php     } ?>
<?php     if ($item->email === null) { ?>
            <td>N/A</td>
<?php     } else if ((int)$item->share_email === 0) { ?>
            <td>(not public)</td>
<?php     } else { ?>
            <td><?php echo htmlspecialchars(str_replace('@', '[#at]', $item->email)); ?></td>
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
    $("#filter_users").submit(function() {
        var name = rawurlencode($("#filter_users input[name=name]").val());
        var school = rawurlencode($("#filter_users input[name=school]").val());
        window.location = "user/search/" + name + ":" + school;
        return false;
    });
</script>
