<?php
namespace Gentor\Speedy\Components;

use Gentor\Speedy\Traits\Enum;

class PayerType
{

    use Enum;

    const Sender = 0;

    const Receiver = 1;

    const ThirdParty = 2;

}