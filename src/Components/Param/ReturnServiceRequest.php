<?php
namespace Gentor\Speedy\Components\Param;

use Gentor\Speedy\Components\ComponentInterface;
use Gentor\Speedy\Traits\Serializable;

class ReturnServiceRequest implements ComponentInterface
{

    use Serializable;

    /**
     * Service type id
     * @var int
     */
    public $serviceTypeId;

    /**
     * Number of parcels
     * @var int
     */
    public $parcelsCount;

}