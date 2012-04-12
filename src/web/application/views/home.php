<div>
  <div class="sidebar">
    <div class="container contests">
      <div class="title">Current contest</div>
      <hr />
      <table class="data current">
        <thead>
          <tr>
            <th>Title</th>
            <th style="width: 8em">Time left</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($contests as $item) { ?>
          <tr class="<?php echo alternator('odd', 'even'); ?>">
            <td><?php echo $item->password === null ? '<span class="public">&nbsp;</span>' : '<span class="private">*</span>'; ?><a href="contests/<?php echo $item->id; ?>"><?php echo htmlspecialchars($item->title); ?></a></td>
            <td class="time_left" end_time="<?php $tmp = new DateTime($item->end_time); echo $tmp->getTimestamp(); ?>000"></td>
          </tr>
<?php } ?>
        </tbody>
      </table>
    </div>
    <div class="container problems">
      <div class="title">Recent added problems</div>
      <hr />
      <table class="data">
        <thead>
          <tr>
            <th>Origin</th>
            <th>Title</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($problems as $item) { ?>
          <tr class="<?php echo alternator('odd', 'even'); ?>">
            <td><a href="<?php echo $item->original_url; ?>"><?php echo htmlspecialchars($item->original_site); ?> - <?php echo htmlspecialchars($item->original_id); ?></a></td>
            <td style="text-align: left"><a href="problems/view/<?php echo $item->id; ?>"><?php echo $item->title; ?></a></td>
          </tr>
<?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="home">
    <div>
    </div>
  </div>
</div>
<script type="text/javascript">
    var gap = <?php echo $now->getTimestamp(); ?>000 - new Date().valueOf();
    function updateTime() {
        $(".time_left").html(function() {
            var timeLeft = $(this).attr("end_time") - (new Date().valueOf() + gap);
            var d = Math.floor(timeLeft / 86400000);
            var h = Math.floor(timeLeft / 3600000) % 24;
            var i = Math.floor(timeLeft / 60000) % 60;
            var s = Math.floor(timeLeft / 1000) % 60;
            return d + " Days " + new Date(1970, 0, 1, h, i, s).format("HH:MM:ss");
        });
    }
    updateTime();
    setInterval(updateTime, 1000);
</script>
