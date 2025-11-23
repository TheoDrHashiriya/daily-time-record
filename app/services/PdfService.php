<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
	public static function streamPdf(string $filename, $viewPaths, array $data = [], bool $download = false)
	{
		if (!is_array($viewPaths))
			$viewPaths = [$viewPaths];

		extract($data);

		$html = '';
		foreach ($viewPaths as $viewPath) {
			ob_start();
			require __DIR__ . "/../views/{$viewPath}";
			$html .= ob_get_clean();
		}

		$dompdf = self::generatePdfString($html);
		$dompdf->stream($filename, ["Attachment" => $download]);
		exit();
	}

	public static function initializeDompdf()
	{
		$options = new Options();
		$options->set("isRemoteEnabled", true);

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