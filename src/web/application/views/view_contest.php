<div>
  <div class="contest">
<?php if ($need_password) { ?>
    <div class="container need_password">
      <form class="need_password" action="" method="post">
        <div class="error">You need password to view this contest.</div>
        <table>
          <tbody>
            <tr>
              <td class="field">Password</td>
              <td><input style="width: 12em;" name="password" type="password" value="" /></td>
            </tr>
            <tr>
              <td colspan="2" class="op"><input type="submit" value="Submit" /></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
<?php } else { ?>
    <table class="info">
      <tbody>
        <tr>
          <td class="field">Start at:</td>
          <td><?php echo $contest->start_time; ?></td>
          <td class="field">End at:</td>
          <td><?php echo $contest->end_time; ?></td>
        </tr>
        <tr>
          <td class="field">Current: </td>
          <td class="current_time"></td>
          <td class="field">Status: </td>
          <td class="<?php echo strtolower($status); ?>"><?php echo $status; ?></td>
        </tr>
        <tr>
          <td class="field">Type: </td>
<?php     $type = ($contest->password === null ? 'Public' : 'Private'); ?>
          <td class="<?php echo strtolower($type); ?>"><?php echo $type; ?></td>
          <td class="field">Manager: </td>
          <td><a href="user/profile/<?php echo $contest->username; ?>"><?php echo $contest->username; ?></a></td>
        </tr>
      </tbody>
    </table>
<?php     if ($status !== 'Upcoming' || (int)$contest->user_id === $this->session->userdata('user_id') || can_admin($this->session->userdata('privilege'))) { ?>
    <div class="problems">
      <table class="data fixed">
        <thead>
          <tr>
            <th style="width: 6em">Problem</th>
            <th style="width: 14em">Origin</th>
            <th>Title</th>
            <th style="width: 14em" colspan="2">Statistics (Yes/All)</th>
          </tr>
        </thead>
        <tbody>
<?php         foreach ($problems as $problem) { ?>
          <tr class="<?php echo alternator('odd', 'even'); ?>">
            <td><a href="contests/<?php echo $contest->id; ?>/problem/<?php echo $problem->flag; ?>"><?php echo $problem->flag; ?></a></td>
<?php             if ($status === 'Ended' || (int)$contest->user_id === $this->session->userdata('user_id') || can_admin($this->session->userdata('privilege'))) { ?>
            <td><div><a href="<?php echo $problem->original_url; ?>"><?php echo htmlspecialchars($problem->original_site); ?> - <?php echo htmlspecialchars($problem->original_id); ?></a></div></td>
<?php             } else { ?>
            <td>N/A</td>
<?php             } ?>
            <td style="text-align: left"><div><a href="contests/<?php echo $contest->id; ?>/problem/<?php echo $problem->flag; ?>"><?php echo $problem->title; ?></a></div></td>
            <td><?php echo $problem->solutions; ?>/<?php echo $problem->submissions; ?></td>
            <td style="text-align: right"><?php echo sprintf('%.2f', $problem->submissions > 0 ? $problem->solutions * 100.0 / $problem->submissions : 0); ?>%</td>
          </tr>
<?php         } ?>
        </tbody>
      </table>
    </div>
<?php     } ?>
<?php     if ($contest->description !== null) { ?>
    <div class="description">
      <table class="data"><tbody><tr><td><?php echo htmlspecialchars($contest->description); ?></td></tr></tbody></table>
    </div>
<?php     } ?>
<?php } ?>
  </div>
</div>
<script type="text/javascript">
    $("form.need_password").submit(function() {
        var password = $("[name=password]", $(this)).val();
        $("[name=password]", $(this)).val(hex_md5(password));
        return true;
    });
    var gap = <?php echo $now->getTimestamp(); ?>000 - new Date().valueOf();
    function updateTime() {
        $(".current_time").text(new Date(new Date().valueOf() + gap).format("yyyy-mm-dd HH:MM:ss"));
    }
    updateTime();
    setInterval(updateTime, 1000);
</script>
