<div>
  <div>
    <div class="container register">
      <div class="title">Create a new DumbOJ account</div>
      <hr />
      <form class="register" action="" method="post">
        <input name="key" type="hidden" value="" />
        <div class="error"><?php echo validation_errors(); ?></div>
        <table>
          <tbody>
            <tr>
              <td class="field">Username</td>
              <td><input style="width: 12em" name="username" value="<?php echo set_value('username'); ?>" /> <span class="required">*</span></td>
            </tr>
            <tr>
              <td class="field">Password</td>
              <td><input style="width: 12em" name="password" type="password" value="" /> <span class="required">*</span></td>
            </tr>
            <tr>
              <td class="field">Confirm password</td>
              <td><input style="width: 12em" name="confirm_password" type="password" value="" /> <span class="required">*</span></td>
            </tr>
            <tr>
              <td class="field">Real name</td>
              <td><input style="width: 18em" name="real_name" value="<?php echo set_value('real_name'); ?>" /></td>
            </tr>
            <tr>
              <td class="field">School</td>
              <td><input style="width: 18em" name="school" value="<?php echo set_value('school'); ?>" /></td>
            </tr>
            <tr>
              <td class="field">Email</td>
              <td>
                <input style="width: 18em" name="email" value="<?php echo set_value('email'); ?>" />
                <input type="checkbox" value="true" name="share_email"<?php echo set_checkbox('share_email', 'true', true); ?> />is public
              </td>
            </tr>
            <tr>
              <td class="field"></td>
              <td><input type="checkbox" value="true" name="share_code"<?php echo set_checkbox('share_code', 'true', true); ?> />I'd like to share my source code.</td>
            </tr>
            <tr>
              <td class="op" colspan="2"><input type="submit" value="Register" /></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    $("form.register").submit(function() {
        var password1 = $("[name=password]", $(this)).val();
        var password2 = $("[name=confirm_password]", $(this)).val();
        $("[name=password]", $(this)).val("*".repeat(password1.length));
        if (password1 == password2) {
            $("[name=confirm_password]", $(this)).val("*".repeat(password2.length));
        } else {
            $("[name=confirm_password]", $(this)).val("#".repeat(password2.length));
        }
        $("[name=key]", $(this)).val(hex_md5(password1));
        return true;
    });
</script>
