<div>
  <div class="container add_contest">
    <div class="title">Add a new contest</div>
    <hr />
    <form class="add_contest" action="" method="post">
      <input name="key" type="hidden" value="" />
      <div class="error"><?php echo validation_errors(); ?></div>
      <table style="width: 50%; float: left">
        <tbody>
          <tr>
            <td class="field" style="width: 30%">Title</td>
            <td><input style="width: 80%" name="title" value="<?php echo set_value('title'); ?>" /> <span class="required">*</span></td>
          </tr>
          <tr>
            <td class="field">Start at</td>
            <td>
              <input style="width: 8em" name="start_time_d" value="<?php echo set_value('start_time_d'); ?>" />
              <input style="width: 2em" name="start_time_h" value="<?php echo set_value('start_time_h'); ?>" /> :
              <input style="width: 2em" name="start_time_i" value="<?php echo set_value('start_time_i'); ?>" /> :
              <input style="width: 2em" disabled="disabled" value="00" />
              <span class="required">*</span><br />
              like 2012-01-01 00:00:00
            </td>
          </tr>
          <tr>
            <td class="field">Duration</td>
            <td>
              <input style="width: 2em" name="duration_d" value="<?php echo set_value('duration_d'); ?>" /> Days
              <input style="width: 2em" name="duration_h" value="<?php echo set_value('duration_h'); ?>" /> :
              <input style="width: 2em" name="duration_i" value="<?php echo set_value('duration_i'); ?>" /> :
              <input style="width: 2em" disabled="disabled" value="00" />
              <span class="required">*</span>
            </td>
          </tr>
          <tr>
            <td class="field">Password</td>
            <td><input style="width: 12em" name="password" type="password" value="" /><br />Blank for public contest</td>
          </tr>
          <tr>
            <td class="field">Description</td>
            <td><textarea name="description"><?php echo set_value('description'); ?></textarea></td>
          </tr>
          <tr>
            <td class="field">Announcement</td>
            <td><textarea name="announcement"><?php echo set_value('announcement'); ?></textarea></td>
          </tr>
        </tbody>
      </table>
      <table class="problems" style="width: 50%; float: left">
        <thead>
          <tr>
            <th style="width: 10%"><a class="add_problem" href="javascript:void(0)"><img style="width: 1em; height: 1em" src="images/add.png" /></a></th>
            <th style="width: 30%">Site</th>
            <th style="width: 50%">ID</th>
            <th style="width: 10%"></th>
          </tr>
        </thead>
        <tbody>
<?php if ($count === 0) { ?>
          <tr class="problem">
            <td class="flag">A</td>
            <td>
              <select name="sites[]">
<?php
          foreach (get_available_sites() as $site) {
              if ($site === 'All') {
                  continue;
              }
?>
                <option value="<?php echo form_prep($site); ?>"><?php echo htmlspecialchars($site); ?></option>
<?php     } ?>
              </select>
            </td>
            <td><input name="ids[]" value="" /></td>
            <td><a class="remove_problem" href="javascript:void(0)"><img style="width: 1em; height: 1em" src="images/remove.png" /></a></td>
          </tr>
<?php } else { ?>
<?php     for ($i = 0; $i < $count; ++$i) { ?>
          <tr class="problem">
            <td class="flag"><?php echo chr(ord('A') + $i); ?></td>
            <td>
              <select name="sites[]">
<?php
              $current_site = set_value('sites[]');
              foreach (get_available_sites() as $site) {
                  if ($site === 'All') {
                      continue;
                  }
                  $selected = ($current_site === form_prep($site) ? ' selected="selected"' : '');
?>
                <option value="<?php echo form_prep($site); ?>"<?php echo $selected; ?>><?php echo htmlspecialchars($site); ?></option>
<?php         } ?>
              </select>
            </td>
            <td><input name="ids[]" value="<?php echo set_value('ids[]'); ?>" /></td>
            <td><a class="remove_problem" href="javascript:void(0)"><img style="width: 1em; height: 1em" src="images/remove.png" /></a></td>
          </tr>
<?php     } ?>
<?php } ?>
        </tbody>
      </table>
      <div style="clear: both; text-align: center"><input type="submit" value="Add Contest" /></div>
    </form>
  </div>
</div>
<script type="text/javascript">
    $("form.add_contest").submit(function() {
        var password = $("input[name=password]", $(this)).val();
        $("input[name=password]", $(this)).val("*".repeat(password.length));
        $("input[name=key]", $(this)).val(hex_md5(password));
        return true;
    });
    $("a.add_problem").click(function() {
        var count = $("table.problems tr.problem").length;
        if (count <= 0 || count >= 26) {
            return;
        }
        var $last = $("table.problems tr.problem:last");
        var $problem = $last.clone();
        $("td:first", $problem).text(String.fromCharCode("A".charCodeAt(0) + count));
        $("[name=\"sites[]\"]", $problem).val($("[name=\"sites[]\"]", $last).val());
        $id = $("[name=\"ids[]\"]", $problem);
        $id.val($id.val().match(/^\s*\d+\s*$/) ? parseInt($id.val()) + 1 : "");
        $("table.problems tbody").append($problem);
    });
    $("table.problems tr.problem .remove_problem").live("click", function() {
        var count = $("table.problems tr.problem").length;
        if (count <= 1) {
            return;
        }
        $(this).parent().parent().remove();
        $("table.problems tr.problem").each(function(index) {
            $("td:first", $(this)).text(String.fromCharCode("A".charCodeAt(0) + index));
        });
    });
</script>
