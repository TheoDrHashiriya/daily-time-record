<?php
namespace App\Services;

use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QRCodeService
{
	public static function _init()
	{
		$options = new QROptions();

		$options->outputType = QROutputInterface::GDIMAGE_PNG;
		$options->quality = 90;
		$options->scale = 7;
		$options->drawLightModules = true;
		$options->circleRadius = 1;
		$options->quietzoneSize = 1;

		return new QRCode($options);
	}

	public static function render($data)
	{
		return self::_init()->render($data);
	}
}