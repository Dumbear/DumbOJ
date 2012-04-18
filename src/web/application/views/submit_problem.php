<div>
  <div class="container submit_problem">
    <div class="title">Submit your solution</div>
    <hr />
    <form action="" method="post">
      <div class="error"><?php echo validation_errors(); ?></div>
      <table>
        <tbody>
          <tr>
            <td class="field">Problem</td>
            <td><a href="problems/view/<?php echo $problem->id ?>"><?php echo htmlspecialchars($problem->title); ?></a> from <a href="<?php echo $problem->original_url; ?>"><?php echo htmlspecialchars($problem->original_site); ?> - <?php echo htmlspecialchars($problem->original_id); ?></a></td>
          </tr>
          <tr>
            <td class="field">Language</td>
            <td>
              <select name="language">
<?php foreach ($languages as $value => $language) { ?>
                <option value="<?php echo $value; ?>"<?php echo set_select('language', $value, $value === $this->session->userdata('language_value')); ?>><?php echo htmlspecialchars($language); ?></option>
<?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td class="field">Source code</td>
            <td>
              <input type="checkbox" value="true" name="share_code"<?php echo set_checkbox('share_code', 'true', $this->session->userdata('share_code') === 1); ?> />I'd like to share this code.
              <textarea class="source_code" name="source_code"><?php echo set_value('source_code'); ?></textarea>
            </td>
          </tr>
          <tr>
            <td colspan="2" class="op">
              <input type="submit" value="Submit" />
              <input type="reset" value="Reset" />
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
