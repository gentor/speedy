<?php
namespace Gentor\Speedy\Components\Param;

use Gentor\Speedy\Components\ComponentInterface;
use Gentor\Speedy\Traits\Serializable;

class ReturnVoucher implements ComponentInterface
{

    use Serializable;

    /**
     * Service type id
     * @var int
     */
    public $serviceTypeId;

    /**
     * Payer type of the new bill of lading (0=sender, 1=receiver or 2=third party).
     * @var int
     */
    public $payerType;

}