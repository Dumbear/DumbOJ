<div>
  <div>
    <div class="container dark" style="padding: 1px">
      <div style="padding: 0.25em 0.5em">Problems from:
        <select name="site">
<?php
      foreach (get_available_sites() as $site) {
          $selected = ($current_site === $site ? ' selected="selected"' : '');
?>
          <option value="<?php echo rawurlencode($site); ?>"<?php echo $selected; ?>><?php echo htmlspecialchars($site); ?></option>
<?php } ?>
        </select>
        &nbsp;&nbsp;Add problems:
        <form class="add_problems" style="display: inline" action="problems/add" method="post">
          <input name="original_site" type="hidden" value="" />
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
              <th>Title</th>
              <th>Source</th>
              <th style="width: 10em">Creation Time</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($problems as $item) { ?>
            <tr class="<?php echo alternator('odd', 'even'); ?>">
              <td><div><a href="<?php echo $item->original_url; ?>"><?php echo htmlspecialchars($item->original_site); ?> - <?php echo htmlspecialchars($item->original_id); ?></a></div></td>
              <td style="text-align: left"><div><a href="problems/view/<?php echo $item->id; ?>"><?php echo $item->title; ?></a></div></td>
              <td style="text-align: left"><div><?php echo $item->source; ?></div></td>
              <td><?php $tmp = new DateTime($item->creation_time); echo $tmp->format('Y-m-d'); ?></td>
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
    $("select[name=site]").change(function() {
        window.location = $("base").attr("href") + "problems/index/" + $(this).val();
    });
    $("form.add_problems").submit(function() {
        $("[name=original_site]", $(this)).val($("select[name=site]").val());
    });
</script>
