<?php
 
class PdfGenerator
{
  public function generate($html,$filename,$size)
  {
    define('DOMPDF_ENABLE_AUTOLOAD', true);
    require_once("./vendor/dompdf/dompdf_config.inc.php");
    
    $dompdf = new dompdf();
    $dompdf->load_html($html);
    $dompdf->set_paper($size, 'landscape');
    $dompdf->render();
    $dompdf->stream($filename.'.pdf',array("Attachment"=>0)); 
  }
}