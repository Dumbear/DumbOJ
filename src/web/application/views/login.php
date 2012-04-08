<!--IS OK-->
<div>
  <div class="container login">
    <div class="title">Login into DumbOJ</div>
    <hr />
    <form id="login" action="" method="post">
      <input name="key" id="key" type="hidden" value="" />
      <input name="salt" id="salt" type="hidden" value="<?php echo $salt ?>" />
<?php if ($need_to_login) { ?>
      <div class="error">You need to login to continue.</div>
<?php } ?>
      <div class="error"><?php echo validation_errors(); ?></div>
      <table>
        <tbody>
          <tr>
            <td class="field" style="width: 40%">Username</td>
            <td><input style="width: 12em;" name="username" id="username" value="<?php echo set_value('username'); ?>" /></td>
          </tr>
          <tr>
            <td class="field">Password</td>
            <td><input style="width: 12em;" name="password" id="password" type="password" value="" /></td>
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
    $("#login").submit(function() {
        var password = $("#password").val();
        var salt = $("#salt").val();
        $("#key").val(hashPassword(password, salt));
        $("#password").val("");
        return true;
    });
</script>
