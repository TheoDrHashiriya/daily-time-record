<?php if (!$_SESSION["has_timed_in_today"]): ?>
	<div class="card" id="time-in">
		<a href="timein" id="time-in">Time In</a>
	</div>
<?php endif;
if (!$_SESSION["has_timed_out_today"]): ?>
	<div class="card" id="time-out">
		<a href="timeout" id="time-in">Time Out</a>
	</div>
<?php endif;