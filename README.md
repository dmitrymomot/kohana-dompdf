html2pdf
========

Simple HTML to PDF converter based on DOMPDF for Kohana Framework 3.2

---------------------------------------------------------------------
``Example``
<pre>
	// Create html from your template
	$html = View::factory('site/feedback/form/1')->render();
	
	// Create class instance PDF
	$pdf = PDF::factory($html);
	
	// Render and save pdf
	$pdf = $pdf->render()->save('your/path/upload', 'filename'); 
	
	// OR
	
	// Render and output client pdf-file
	$pdf = $pdf->render()->load('filename'); 
	
</pre>