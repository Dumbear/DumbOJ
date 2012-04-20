<div>
  <div class="container login">
    <div class="title">Login into DumbOJ</div>
    <hr />
    <form class="login" action="" method="post">
      <input name="key" type="hidden" value="" />
      <input name="salt" type="hidden" value="<?php echo $salt ?>" />
<?php if ($this->session->flashdata('need_to_login') === 'true') { ?>
      <div class="error">You need to login to continue.</div>
<?php } ?>
      <div class="error"><?php echo validation_errors(); ?></div>
      <table>
        <tbody>
          <tr>
            <td class="field" style="width: 40%">Username</td>
            <td><input style="width: 12em" name="username" value="<?php echo set_value('username'); ?>" /></td>
          </tr>
          <tr>
            <td class="field">Password</td>
            <td><input style="width: 12em" name="password" type="password" value="" /></td>
          </tr>
          <tr>
            <td colspan="2" class="op"><input type="submit" value="Login" /></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript">
    $("form.login").submit(function() {
        var password = $("[name=password]", $(this)).val();
        var salt = $("[name=salt]", $(this)).val();
        $("[name=key]", $(this)).val(hashPassword(password, salt));
        $("[name=password]", $(this)).val("");
        return true;
    });
</script>
