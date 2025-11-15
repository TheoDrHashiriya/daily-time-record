<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfHelper
{
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
		$dompdf = PdfHelper::initializeDompdf();
		$dompdf->loadHtml($html);
		$dompdf->render();
		$dompdf->stream($filename);
	}

	public static function generatePdfString($html)
	{
		$dompdf = PdfHelper::initializeDompdf();
		$dompdf->loadHtml($html);
		$dompdf->render();
		return $dompdf;
	}
}