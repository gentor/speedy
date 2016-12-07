<?php
namespace Gentor\Speedy\Components\Param;

use Gentor\Speedy\Components\ComponentInterface;
use Gentor\Speedy\Traits\Serializable;

class BarcodeInfo implements ComponentInterface
{

    use Serializable;

    /**
     * Barcode value. For barcode formats other than 'CODE128' it must contain digits only.
     * @var string
     */
    public $barcodeValue;

    /**
     * Barcode label. It is printed just below the barcode image. For barcode formats other than 'CODE128'
     * barcodeLabel must be equal to barcodeValue.
     * @var string
     */
    public $barcodeLabel;

}