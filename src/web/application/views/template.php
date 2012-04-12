<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <base href="<?php echo site_url(); ?>" />
  <link href="css/default.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
  <script type="text/javascript" src="js/md5-min.js"></script>
  <script type="text/javascript" src="js/common.js"></script>
<?php if (isset($meta_sh)) { ?>
  <script type="text/javascript" src="sh/scripts/shCore.js"></script>
  <script type="text/javascript" src="sh/src/shAutoloader.js"></script>
  <link href="sh/styles/shCore.css" rel="stylesheet" type="text/css" />
  <link href="sh/styles/shThemeDefault.css" rel="stylesheet" type="text/css" />
<?php } ?>
</head>
<body>
<div>
  <div class="logo">
    <a href=""><img src="images/dumboj-logo.png" /></a>
  </div>
  <div class="user-op">
<?php if ($this->session->userdata('user_id') === false) { ?>
    <a href="user/login">Login</a>
    |
    <a href="user/register">Register</a>
<?php } else { ?>
    <a href="user/profile"><?php echo $this->session->userdata('username'); ?></a>
    |
    <a href="user/logout">Logout</a>
<?php } ?>
  </div>
  <div style="clear: both"></div>
</div>
<div class="container menu">
  <ul class="menu">
    <li><a href="">Home</a></li>
    <li><a href="contests">Contests</a></li>
    <li><a href="problems">Problems</a></li>
    <li><a href="problems/status">Status</a></li>
    <li><a href="user/ranklist">Ranklist</a></li>
  </ul>
  <div style="clear: both"></div>
</div>
<?php echo $content; ?>
<?php if ($this->session->flashdata('message') !== false) { ?>
<script type="text/javascript">
    alert("<?php echo $this->session->flashdata('message'); ?>");
</script>
<?php } ?>
<?php if (isset($meta_sh)) { ?>
<script type="text/javascript">
    SyntaxHighlighter.autoloader(
        'cpp sh/scripts/shBrushCpp.js',
        'pascal sh/scripts/shBrushDelphi.js',
        'java sh/scripts/shBrushJava.js'
    );
    SyntaxHighlighter.all();
</script>
<?php } ?>
</body>
</html>
