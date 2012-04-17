<div>
  <div>
    <div class="container dark" style="padding: 1px">
      <div style="padding: 0.25em 0.5em">Problems from:
        <select id="site">
<?php
      foreach (get_available_sites() as $site) {
          $selected = ($current_site === $site ? ' selected="selected"' : '');
?>
          <option value="<?php echo rawurlencode($site); ?>"<?php echo $selected; ?>><?php echo htmlspecialchars($site); ?></option>
<?php } ?>
        </select>
        &nbsp;&nbsp;Add problems:
        <form style="display: inline" id="add_problems" action="problems/add" method="post">
          <input name="original_site" id="original_site" type="hidden" value="" />
          <input style="width: 4em" name="original_id_from" value="" />
          -
          <input style="width: 4em" name="original_id_to" value="" />
          <input type="submit" value="Add"<?php if ($current_site === 'All') echo ' disabled="disabled"'; ?> />
        </form>
      </div>
      <div class="container" style="margin: 0">
        <div class="pagination"><?php echo $pagination; ?></div>
        <table class="data fixed">
          <thead>
            <tr>
              <th style="width: 14em">Origin</th>
              <th style="width: 40%">Title</th>
              <th style="width: ">Source</th>
              <th style="width: 10em">Creation Time</th>
            </tr>
          </thead>
          <tbody>
<?php
      foreach ($problems as $problem) {
          $tr_class = alternator('odd', 'even');
          $original_site = htmlspecialchars($problem->original_site);
          $original_id = htmlspecialchars($problem->original_id);
          $creation_time = 'N/A';
          if ($problem->creation_time !== null) {
              $creation_time = new DateTime($problem->creation_time);
              $creation_time = $creation_time->format('Y-m-d');
          }
?>
            <tr class="<?php echo $tr_class; ?>">
              <td><div><a href="<?php echo $problem->original_url; ?>"><?php echo $original_site; ?> - <?php echo $original_id; ?></a></div></td>
              <td style="text-align: left"><div><a href="problems/view/<?php echo $problem->id; ?>"><?php echo $problem->title; ?></a></div></td>
              <td style="text-align: left"><div><?php echo $problem->source; ?></div></td>
              <td><?php echo $creation_time; ?></td>
            </tr>
<?php } ?>
          </tbody>
        </table>
        <div class="pagination"><?php echo $pagination; ?></div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $("#site").change(function() {
        window.location = $("base").attr("href") + "problems/index/" + $(this).val();
    });
    $("#add_problems").submit(function() {
        $("#original_site").val($("#site").val());
    });
</script>
