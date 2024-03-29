<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

A PHP Error was encountered

Severity:    <?= $severity, "\n"; ?>
Message:     <?= $message, "\n"; ?>
Filename:    <?= $filepath, "\n"; ?>
Line Number: <?= $line; ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE): ?>

Backtrace:
<?php	foreach (debug_backtrace() as $error): ?>
<?php		if (isset($error['file']) && strpos($error['file'], (string) realpath(BASEPATH)) !== 0): ?>
	File: <?= $error['file'], "\n"; ?>
	Line: <?= $error['line'], "\n"; ?>
	Function: <?= $error['function'], "\n\n"; ?>
<?php		endif ?>
<?php	endforeach ?>

<?php endif ?>
