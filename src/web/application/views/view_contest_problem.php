<div class="contest">
  <div class="problem">
    <div class="problem_list">
      <table class="data fixed">
        <thead>
          <tr>
<?php foreach ($problems as $item) { ?>
<?php     if ($item->flag === $problem->flag) { ?>
            <th><?php echo $item->flag; ?></th>
<?php     } else { ?>
            <th><a href="contests/<?php echo $contest->id; ?>/problem/<?php echo $item->flag; ?>"><?php echo $item->flag; ?></a></th>
<?php     } ?>
<?php } ?>
          </tr>
        </thead>
      </table>
    </div>
    <div class="title"><span class="flag"><?php echo $problem->flag; ?></span> - <?php echo $problem->title; ?></div>
<?php if ($status === 'Ended') { ?>
    <div class="origin"><a href="problems/view/<?php echo $problem->id; ?>"><?php echo htmlspecialchars($problem->original_site); ?> - <?php echo htmlspecialchars($problem->original_id); ?></a></div>
<?php } ?>
    <div class="limit">
      <strong>Time Limit: </strong>
      <?php echo $problem->time_limit === null ? 'N/A' : "{$problem->time_limit}MS"; ?>
      &nbsp;&nbsp;
      <strong>Memory Limit: </strong>
      <?php echo $problem->memory_limit === null ? 'N/A' : "{$problem->memory_limit}KB"; ?>
    </div>
    <div class="op">
      <a href="contests/<?php echo $contest->id; ?>/submit/<?php echo $problem->flag; ?>">Submit</a>
      &nbsp;&nbsp;
      <a href="contests/<?php echo $contest->id; ?>/status/<?php echo $problem->id; ?>:::">Status</a>
    </div>
<?php if ($problem_content->description !== null) { ?>
    <div>
      <div class="field">Description</div>
      <div class="container"><?php echo $problem_content->description; ?></div>
    </div>
<?php } ?>
<?php if ($problem_content->input !== null) { ?>
    <div>
      <div class="field">Input</div>
      <div class="container"><?php echo $problem_content->input; ?></div>
    </div>
<?php } ?>
<?php if ($problem_content->output !== null) { ?>
    <div>
      <div class="field">Output</div>
      <div class="container"><?php echo $problem_content->output; ?></div>
    </div>
<?php } ?>
<?php if ($problem_content->sample_input !== null) { ?>
    <div>
      <div class="field">Sample Input</div>
      <div class="container"><?php echo $problem_content->sample_input; ?></div>
    </div>
<?php } ?>
<?php if ($problem_content->sample_output !== null) { ?>
    <div>
      <div class="field">Sample Output</div>
      <div class="container"><?php echo $problem_content->sample_output; ?></div>
    </div>
<?php } ?>
<?php if ($problem_content->hint !== null) { ?>
    <div>
      <div class="field">Hint</div>
      <div class="container"><?php echo $problem_content->hint; ?></div>
    </div>
<?php } ?>
    <div class="op">
      <a href="contests/<?php echo $contest->id; ?>/submit/<?php echo $problem->flag; ?>">Submit</a>
      &nbsp;&nbsp;
      <a href="contests/<?php echo $contest->id; ?>/status/<?php echo $problem->id; ?>:::">Status</a>
    </div>
  </div>
</div>
