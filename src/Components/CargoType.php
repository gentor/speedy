<?php
namespace Gentor\Speedy\Components;

use Gentor\Speedy\Traits\Enum;

class CargoType
{

    use Enum;

    const Parcel = 'CARGO_TYPE_PARCEL';

    const Pallet = 'CARGO_TYPE_PALLET';

}