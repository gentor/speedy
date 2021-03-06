<?php
namespace Gentor\Speedy\Components\Param;

use Illuminate\Support\Collection;
use Gentor\Speedy\Components\ComponentInterface;
use Gentor\Speedy\Traits\Serializable;

class ClientData implements ComponentInterface
{

    use Serializable;

    /**
     * Client/Partner ID
     * @var int|null
     */
    public $clientId;

    /**
     * Name of the client (company or private person)
     * @var string
     */
    public $partnerName;

    /**
     * Company department/office
     * @var string
     */
    public $objectName;

    /**
     * Address details
     * @var Address
     */
    public $address;

    /**
     * Contact name
     * @var string
     */
    public $contactName;

    /**
     * Phone numbers
     * @var Collection<PhoneNumber>
     */
    public $phones;

    /**
     * Email address
     * @var string
     */
    public $email;

    public static function createFromRequest(array $data, $id = null)
    {
        $result = new static;

        $result->clientId = $id ? (float)$id : null;
        $result->partnerName = !$id && isset($data['name']) ? $data['name'] : null;
        $result->objectName = isset($data['object']) ? (int)$data['object'] : null;
        $result->address = !$id ? Address::createFromRequest($data) : null;
        $result->contactName = isset($data['contact']) ? $data['contact'] : null;
        $result->phones = PhoneNumber::createFromRequest($data);
        $result->email = isset($data['email']) ? $data['email'] : null;

        return $result;
    }

}