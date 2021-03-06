<?php
namespace Gentor\Speedy\Components\Param;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Gentor\Speedy\Components\ComponentInterface;
use Gentor\Speedy\Components\Result\CourierService;
use Gentor\Speedy\Components\Result\Site;
use Gentor\Speedy\Traits\Serializable;

class Calculation implements ComponentInterface
{

    use Serializable;

    const RequestMapping = [
        'senderSiteId' => 'sender.settlement',
        'willBringToOfficeId' => 'sender.office',
        'receiverSiteId' => 'receiver.settlement',
        'officeToBeCalledId' => 'receiver.office',
        'weightDeclared' => 'shipment.weight',
    ];

    const TypeMapping = [
        'autoAdjustTakingTime' => 'bool',
        'serviceTypeId' => 'int',
        'willBringToOfficeId' => 'float',
        'broughtToOffice' => 'bool',
        'officeToBeCalledId' => 'int',
        'toBeCalled' => 'bool',
        'fixedTimeDelivery' => 'int',
        'deferredDeliveryWorkDays' => 'int',
        'amountInsuranceBase' => 'float',
        'amountCodBase' => 'float',
        'payCodToThirdParty' => 'bool',
        'parcelsCount' => 'int',
        'weightDeclared' => 'float',
        'documents' => 'bool',
        'fragile' => 'bool',
        'palletized' => 'bool',
        'senderId' => 'int',
        'senderCountryId' => 'int',
        'senderSiteId' => 'int',
        'receiverId' => 'int',
        'receiverCountryId' => 'int',
        'receiverSiteId' => 'int',
        'payerType' => 'int',
        'payerRefId' => 'int',
        'payerTypeInsurance' => 'int',
        'payerRefInsuranceId' => 'int',
        'payerTypePackings' => 'int',
        'payerRefPackingsId' => 'int',
        'specialDeliveryId' => 'int',
        'includeShippingPriceInCod' => 'bool',
        'checkTBCOfficeWorkDay' => 'bool',
    ];

    const SiteMapping = ['senderSiteId', 'receiverSiteId'];
    const CountryMapping = ['senderCountryId', 'receiverCountryId'];

    const Nullable = [
        'willBringToOfficeId',
        'officeToBeCalledId',
        'fixedTimeDelivery',
        'deferredDeliveryWorkDays',
        'amountInsuranceBase',
        'amountCodBase',
        'senderId',
        'senderCountryId',
        'senderSiteId',
        'receiverId',
        'receiverCountryId',
        'receiverSiteId',
        'payerRefId',
        'payerTypeInsurance',
        'payerRefInsuranceId',
        'payerTypePackings',
        'payerRefPackingsId',
        'specialDeliveryId',
    ];

    /**
     * The date for shipment pick-up (the "time" component is ignored). Default value is "today".
     * @var Carbon
     */
    public $takingDate;

    /**
     * If set to true, the "takingDate" field is not just to be validated, but the first allowed (following) date
     * will be used instead (in compliance with the pick-up schedule etc.).
     * @var bool
     */
    public $autoAdjustTakingTime;

    /**
     * Courier service type ID
     * @var int
     */
    public $serviceTypeId;

    /**
     * Specifies the specific Speedy office, where the sender intends to deliver the shipment by him/herself.
     * If willBringToOfficeId is provided, willBringToOffice flag is considered "true" and the picking "from office",
     * regardless the value provided. If willBringToOfficeId is not provied (null) and willBringToOffice flag is "true",
     * willBringToOfficeId is automatically set with default value configured for caller user profile. The default
     * willBringToOfficeId value could be managed using profile configuration page in client's Speedy web site.
     * If willBringToOfficeId is set to 0, broughtToOffice flag is considered "false".
     * @var float|null
     */
    public $willBringToOfficeId;

    /**
     * Specifies if the sender intends to deliver the shipment to a Speedy office by him/herself
     * instead of ordering a visit by courier. If this flag is true the shipment is considered "from office".
     * Otherwise "from address" is considered.
     * @var bool
     */
    public $broughtToOffice;

    /**
     * ID of an office "to be called". Non-null and non-zero value indicates this picking as "to office".
     * Otherwise "to address" is considered. If officeToBeCalledId is provided (non-null and non-zero),
     * toBeCalled flag is considered "true". If officeToBeCalledId is set to 0, toBeCalled flag is considered "false".
     * @var int|null
     */
    public $officeToBeCalledId;

    /**
     * Specifies if the shipment is "to be called". If this flag is true the shipment is considered "to office".
     * Otherwise "to address" is considered.
     * @var bool
     */
    public $toBeCalled;

    /**
     * Fixed time for delivery ("HHmm" format, i.e., the number "1315" means "13:15", "830" means "8:30" etc.)
     * Depending on the courier service, this property could be required, allowed or banned.
     * @var int|null
     */
    public $fixedTimeDelivery;

    /**
     * In some rare cases users might prefer the delivery to be deferred by a day or two. This parameter allows users to
     * specify by how many (working) days they would like to postpone the shipment delivery.
     * @var int|null
     */
    public $deferredDeliveryWorkDays;

    /**
     * Shipment insurance value (if the shipment is insured)
     * @var float|null
     */
    public $amountInsuranceBase;

    /**
     * Cash-on-Delivery (COD) amount
     * @var float|null
     */
    public $amountCodBase;

    /**
     * Specifies if the COD value is to be paid to a third party.
     * Allowed only if the shipment has payerType = 2 (third party).
     * @var bool
     */
    public $payCodToThirdParty;

    /**
     * Parcels count (must be equal to the number of parcels described in List parcels).
     * @var int|null
     */
    public $parcelsCount;

    /**
     * Declared weight (the greater of "volume" and "real" weight values)
     * @var float
     */
    public $weightDeclared;

    /**
     * Specifies whether the shipment consists of documents.
     * @var bool
     */
    public $documents;

    /**
     * Specifies whether the shipment is fragile - necessary when the price of insurance is being calculated.
     * @var bool
     */
    public $fragile;

    /**
     * Specifies whether the shipment is palletized
     * @var bool
     */
    public $palletized;

    /**
     * Sender's ID
     * @var int|null
     */
    public $senderId;

    /**
     * Sender's country ID
     * @var int|null
     */
    public $senderCountryId;

    /**
     * Sender's post code
     * @var string
     */
    public $senderPostCode;

    /**
     * Sender's site ID
     * @var int|null
     */
    public $senderSiteId;

    /**
     * Receiver's ID
     * @var int|null
     */
    public $receiverId;

    /**
     * Receiver's country ID
     * @var int|null
     */
    public $receiverCountryId;

    /**
     * Receiver's post code
     * @var string
     */
    public $receiverPostCode;

    /**
     * Receiver's site ID
     * @var int|null
     */
    public $receiverSiteId;

    /**
     * Payer type (0=sender, 1=receiver or 2=third party)
     * @var int
     */
    public $payerType;

    /**
     * Payer ID
     * @var int|null
     */
    public $payerRefId;

    /**
     * Insurance payer type (0=sender, 1=receiver or 2=third party)
     * @var int|null
     */
    public $payerTypeInsurance;

    /**
     * Insurance payer ID
     * @var int|null
     */
    public $payerRefInsuranceId;

    /**
     * Packings payer type (0=sender, 1=receiver or 2=third party)
     * @var int|null
     */
    public $payerTypePackings;

    /**
     * Packings payer ID
     * @var int|null
     */
    public $payerRefPackingsId;

    /**
     * Information about parcles
     * @var Collection<ParcelInfo>
     */
    public $parcels = null;

    /**
     * A special delivery ID
     * @var int|null
     */
    public $specialDeliveryId;

    /**
     * Flag indicating whether the shipping price should be included into the cash on delivery price.
     * @var bool
     */
    public $includeShippingPriceInCod;

    /**
     * Check if specified office to be called is working. Default value - true
     * @var bool
     */
    public $checkTBCOfficeWorkDay;

    public function __construct()
    {
        $this->parcels = new Collection();
    }

    public static function createFromRequest(array $data)
    {
        $result = new static;

        foreach ($result as $expected => $default) {
            $result->$expected = Arr::has($data, $expected) ? Arr::get($data, $expected) : null;
        }

        foreach (static::RequestMapping as $expected => $mapping) {
            $result->$expected = Arr::has($data, $mapping) ? Arr::get($data, $mapping) : null;
        }

        $result->mapSites();
        $result->mapCountries();

        foreach (static::TypeMapping as $expected => $type) {
            $method = $type . 'val';
            $result->$expected = $method($result->$expected);
        }

        $result->mapNullable();

        if (!$result->serviceTypeId) {
            $result->serviceTypeId = CourierService::DefaultServiceId;
        }

        if ($result->willBringToOfficeId) {
            $result->senderCountryId = null;
            $result->senderSiteId = null;
        }

        if ($result->officeToBeCalledId) {
            $result->receiverCountryId = null;
            $result->receiverSiteId = null;
        }

        if (!$result->parcelsCount) {
            $result->parcelsCount = 1;
        }

        return $result;
    }

    protected function mapSites()
    {
        foreach (static::SiteMapping as $index => $expected) {
            if ((string)(int)$this->$expected != $this->$expected) {
                $site = Site::find($this->$expected);

                if ($site instanceof Site) {
                    $this->$expected = $site->id;
                    $this->{static::CountryMapping[$index]} = $site->countryId;
                }
            }
        }
    }

    protected function mapCountries()
    {
        foreach (static::CountryMapping as $index => $expected) {
            if (!$this->$expected) {
                $site = Site::getById($this->{static::SiteMapping[$index]});

                if ($site instanceof Site) {
                    $this->{static::CountryMapping[$index]} = $site->countryId;
                }
            }
        }
    }

    protected function mapNullable()
    {
        foreach (static::Nullable as $expected) {
            if (!$this->$expected) {
                $this->$expected = null;
            }
        }
    }

}