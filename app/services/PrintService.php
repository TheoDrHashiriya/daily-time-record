<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class PrintService
{

	public static function streamPdf(string $filename, $views, array $data = [], bool $download = false)
	{
		if (!is_array($views))
			$views = [$views];

		extract($data);

		$html = '';
		foreach ($views as $view) {
			ob_start();
			require_once VIEWS_PATH . '/' . $view . ".php";
			$html .= ob_get_clean();
		}

		$dompdf = self::generatePdfString($html);
		$dompdf->stream($filename . ".pdf", ["Attachment" => $download]);
		exit();
	}

	public static function initializeDompdf()
	{
		$options = new Options();

		$options->set("isHTML5ParserEnabled", true);
		$options->set("isRemoteEnabled", true);

		$options->setFontDir(realpath(FONTS_PATH));
		$options->setFontCache(realpath(FONTS_PATH));

		$dompdf = new Dompdf($options);
		$dompdf->setPaper("A4", "portrait");
		return $dompdf;
	}

	public static function generatePdf($html, $filename = "document.pdf")
	{
		$dompdf = self::initializeDompdf();
		$dompdf->loadHtml($html);
		$dompdf->render();
		$dompdf->stream($filename);
	}

	public static function generatePdfString($html)
	{
		$dompdf = self::initializeDompdf();
		$dompdf->loadHtml($html);
		$dompdf->render();
		return $dompdf;
	}
}