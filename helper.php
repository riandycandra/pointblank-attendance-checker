<?php
include_once "vendor/autoload.php";

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * print_message
 *
 * @param  mixed $message
 * @param  mixed $type [0 = success, 1 = error, 2 = warning]
 * @return void
 */
function print_message(?string $message = null, int $type = null) {
	$output = new ConsoleOutput();

	// Create a new style for the color
	$errorStyle = new OutputFormatterStyle('red', 'black', ['bold', 'blink']);
	$successStyle = new OutputFormatterStyle('green', 'black', ['bold', 'blink']);
	$warningStyle = new OutputFormatterStyle('yellow', 'black', ['bold', 'blink']);

	// Apply the style to the output
	$output->getFormatter()->setStyle('error', $errorStyle);
	$output->getFormatter()->setStyle('warning', $warningStyle);
	$output->getFormatter()->setStyle('success', $successStyle);

	if ($type == 0) {
		$output->writeLn("<success>{$message}</success>");
	} elseif ($type == 1) {
		$output->writeLn("<error>{$message}</error>");
	} elseif ($type == 2) {
		$output->writeLn("<warning>{$message}</warning>");
	} else {
		return 0;
	}
}
