<?php
namespace Gentor\Speedy\Components\Param;

use Gentor\Speedy\Components\ComponentInterface;
use Gentor\Speedy\Traits\Serializable;

class FilterSite implements ComponentInterface
{

    use Serializable;

    public $countryId;

    public $postCode;

    public $name;

    public $type;

    public $municipality;

    public $region;

    public $searchString;

}