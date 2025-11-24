<?php
require_once('../../plugin/php-image-resize/lib/ImageResize.php');
require_once('../../plugin/php-image-resize/lib/ImageResizeException.php');
use \Gumlet\ImageResize;

$image = new ImageResize('../../../uploads/listings/6/000.jpg');
$image->resize(150,150);
$image->save('../../../uploads/listings/6/thumbs/000.jpg');
?>