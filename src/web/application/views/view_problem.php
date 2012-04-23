<div>
  <div class="problem">
    <div class="title"><?php echo $problem->title; ?></div>
    <div class="origin"><a href="<?php echo $problem->original_url; ?>"><?php echo htmlspecialchars($problem->original_site); ?> - <?php echo htmlspecialchars($problem->original_id); ?></a></div>
    <div class="limit">
      <strong>Time Limit: </strong>
      <?php echo $problem->time_limit === null ? 'N/A' : "{$problem->time_limit}MS"; ?>
      &nbsp;&nbsp;
      <strong>Memory Limit: </strong>
      <?php echo $problem->memory_limit === null ? 'N/A' : "{$problem->memory_limit}KB"; ?>
    </div>
    <div class="op">
      <a href="problems/submit/<?php echo $problem->id; ?>">Submit</a>
      &nbsp;&nbsp;
      <a href="javascript:window.history.back()">Go Back</a>
      &nbsp;&nbsp;
      <a href="problems/status/<?php echo rawurlencode($problem->original_site); ?>:<?php echo rawurlencode($problem->original_id); ?>:::">Status</a>
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
      <div class="container" style="font-family: Courier New, monospace"><?php echo $problem_content->sample_input; ?></div>
    </div>
<?php } ?>
<?php if ($problem_content->sample_output !== null) { ?>
    <div>
      <div class="field">Sample Output</div>
      <div class="container" style="font-family: Courier New, monospace"><?php echo $problem_content->sample_output; ?></div>
    </div>
<?php } ?>
<?php if ($problem_content->hint !== null) { ?>
    <div>
      <div class="field">Hint</div>
      <div class="container"><?php echo $problem_content->hint; ?></div>
    </div>
<?php } ?>
<?php if ($problem->source !== '') { ?>
    <div>
      <div class="field">Source</div>
      <div class="container"><?php echo $problem->source; ?></div>
    </div>
<?php } ?>
    <div class="op">
      <a href="problems/submit/<?php echo $problem->id; ?>">Submit</a>
      &nbsp;&nbsp;
      <a href="javascript:window.history.back()">Go Back</a>
      &nbsp;&nbsp;
      <a href="problems/status/<?php echo rawurlencode($problem->original_site); ?>:<?php echo rawurlencode($problem->original_id); ?>:::">Status</a>
    </div>
  </div>
</div>
