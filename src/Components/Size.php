<?php
namespace Rolice\Speedy\Components;

class Size implements ComponentInterface
{

    use Serializable;

    /**
     * Width (cm)
     * Limit 9999
     * @var int
     */
    public $width;

    /**
     * Height (cm)
     * Limit 9999
     * @var int
     */
    public $height;

    /**
     * Depth (cm)
     * Limit 9999
     * @var int
     */
    public $depth;

}