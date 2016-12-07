<?php
namespace Gentor\Speedy\Components\Param;

use Gentor\Speedy\Components\ComponentInterface;
use Gentor\Speedy\Traits\Serializable;

class Packing implements ComponentInterface
{

    use Serializable;

    /**
     * Reserved for internal use.
     * @var int
     */
    public $packingId;

    /**
     * Reserved for internal use.
     * @var int
     */
    public $count;

}