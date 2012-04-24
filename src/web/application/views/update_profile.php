<div>
  <div>
    <div class="container register">
      <div class="title">Update your profile</div>
      <hr />
      <form class="update_profile" action="" method="post">
        <input name="old_key" type="hidden" value="" />
        <input name="new_key" type="hidden" value="" />
        <input name="salt" type="hidden" value="<?php echo $salt ?>" />
        <div class="error"><?php echo validation_errors(); ?></div>
        <table>
          <tbody>
            <tr>
              <td class="field">Username</td>
              <td><?php echo $user->username; ?></td>
            </tr>
            <tr>
              <td class="field">Old password</td>
              <td><input style="width: 12em" name="old_password" type="password" /> <span class="required">*</span></td>
            </tr>
            <tr>
              <td class="field">New password</td>
              <td><input style="width: 12em" name="new_password" type="password" /></td>
            </tr>
            <tr>
              <td class="field">Confirm password</td>
              <td><input style="width: 12em" name="confirm_password" type="password" /></td>
            </tr>
            <tr>
              <td class="field">Real name</td>
              <td><input style="width: 18em" name="real_name" value="<?php echo set_value('real_name', htmlspecialchars($user->real_name)); ?>" /></td>
            </tr>
            <tr>
              <td class="field">School</td>
              <td><input style="width: 18em" name="school" value="<?php echo set_value('school', htmlspecialchars($user->school)); ?>" /></td>
            </tr>
            <tr>
              <td class="field">Email</td>
              <td>
                <input style="width: 18em" name="email" value="<?php echo set_value('email', htmlspecialchars($user->email)); ?>" />
                <input type="checkbox" value="true" name="share_email"<?php echo set_checkbox('share_email', 'true', $user->share_email === '1'); ?> />is public
              </td>
            </tr>
            <tr>
              <td class="field"></td>
              <td><input type="checkbox" value="true" name="share_code"<?php echo set_checkbox('share_code', 'true', $user->share_code === '1'); ?> />I'd like to share my source code.</td>
            </tr>
            <tr>
              <td class="op" colspan="2"><input type="submit" value="Update" /></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    $("form.update_profile").submit(function() {
        var $old_password = $("[name=old_password]", $(this));
        $("[name=old_key]", $(this)).val(hashPassword($old_password.val(), $("[name=salt]", $(this)).val()));
        $old_password.val("*".repeat($old_password.val().length));
        var $new_password = $("[name=new_password]", $(this));
        var $confirm_password = $("[name=confirm_password]", $(this));
        if ($new_password.val() == $confirm_password.val()) {
            $confirm_password.val("*".repeat($confirm_password.val().length));
            if ($new_password.val().length > 0) {
                $("[name=new_key]", $(this)).val(hex_md5($new_password.val()));
            }
        } else {
            $confirm_password.val("#".repeat($confirm_password.val().length));
        }
        $new_password.val("*".repeat($new_password.val().length));
        return true;
    });
</script>
