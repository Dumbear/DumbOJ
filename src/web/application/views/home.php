<div>
  <div class="sidebar">
    <div class="container contests">
      <div class="title">Current contest</div>
      <hr />
      <table class="data fixed current">
        <thead>
          <tr>
            <th>Title</th>
            <th style="width: 8em">Time left</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($contests as $item) { ?>
          <tr class="<?php echo alternator('odd', 'even'); ?>">
            <td><div><?php echo $item->password === null ? '<span class="public">&nbsp;</span>' : '<span class="private">*</span>'; ?><a href="contests/<?php echo $item->id; ?>"><?php echo htmlspecialchars($item->title); ?></a></div></td>
            <td><div class="time_left" end_time="<?php $tmp = new DateTime($item->end_time); echo $tmp->getTimestamp(); ?>000"></div></td>
          </tr>
<?php } ?>
        </tbody>
      </table>
    </div>
<?php alternator(); ?>
    <div class="container problems">
      <div class="title">Recent added problems</div>
      <hr />
      <table class="data fixed">
        <thead>
          <tr>
            <th style="width: 8em">Origin</th>
            <th>Title</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($problems as $item) { ?>
          <tr class="<?php echo alternator('odd', 'even'); ?>">
            <td><div><a href="<?php echo $item->original_url; ?>"><?php echo htmlspecialchars($item->original_site); ?> - <?php echo htmlspecialchars($item->original_id); ?></a></div></td>
            <td style="text-align: left"><div><a href="problems/view/<?php echo $item->id; ?>"><?php echo $item->title; ?></a></div></td>
          </tr>
<?php } ?>
        </tbody>
      </table>
    </div>
    <div class="container search_users">
      <div class="title">Search users</div>
      <hr />
      <form class="search_users" action="" method="post">
        <table>
          <tbody>
            <tr>
              <td><span>Name:</span></td>
              <td><input style="width: 100%" name="name" /></td>
              <td><input style="width: 100%" type="submit" value="Search" /></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
  <div class="home">
    <div class="container about">
      <div class="title">About DumbOJ</div>
      <hr />
      <p><a href="">DumbOJ</a> is a virtual online judge system and an open source project <a href="https://github.com/Dumbear/DumbOJ">hosted on GitHub</a>. It can obtain problems from other regular online judge systems and simulate submissions. Hence everyone can hold virtual contests based on these problems.</p>
      <p>Currently, the following online judge systems are supported:</p>
      <ul class="sites">
<?php
      foreach (get_available_sites() as $url => $item) {
          if ($item === 'All') {
              continue;
          }
?>
        <li><a href="<?php echo $url; ?>"><?php echo htmlspecialchars($item); ?></a></li>
<?php } ?>
      </ul>
      <div style="clear: left"></div>
    </div>
    <div class="container todo">
      <div class="title">To do list</div>
      <hr />
      <ul style="margin-left: 2em">
        <li>Allow users to edit contest</li>
        <li>Allow searching problems</li>
        <li>Allow administration</li>
      </ul>
    </div>
  </div>
</div>
<script type="text/javascript">
    var gap = <?php echo $now->getTimestamp(); ?>000 - new Date().valueOf();
    function updateTime() {
        $(".time_left").text(function() {
            var timeLeft = $(this).attr("end_time") - (new Date().valueOf() + gap);
            if (timeLeft <= 0) {
                return "00:00:00";
            }
            var h = Math.floor(timeLeft / 3600000);
            var i = Math.floor(timeLeft / 60000) % 60;
            var s = Math.floor(timeLeft / 1000) % 60;
            return h + ":" + (i < 10 ? "0" : "") + i + ":" + (s < 10 ? "0" : "") + s;
        });
    }
    updateTime();
    setInterval(updateTime, 1000);

    $("form.search_users").submit(function() {
        var name = rawurlencode($("[name=name]", $(this)).val());
        window.location = $("base").attr("href") + "user/search/" + name + ":";
        return false;
    });
</script>
