<?php
namespace Gentor\Speedy\Components\Param;

use Gentor\Speedy\Components\ComponentInterface;
use Gentor\Speedy\Traits\Serializable;

class ReturnShipmentRequest implements ComponentInterface
{

    use Serializable;

    /**
     * Insurance base amount
     * @var int|null
     */
    public $amountInsuranceBase;

    /**
     * Fragile flag
     * @var bool|null
     */
    public $fragile;

    /**
     * Number of parcels
     * @var int
     */
    public $parcelsCount;

    /**
     * Service type id
     * @var int
     */
    public $serviceTypeId;


}