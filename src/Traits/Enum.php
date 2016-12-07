<?php
namespace Gentor\Speedy\Traits;

use ReflectionClass;
use Gentor\Speedy\Exceptions\SpeedyException;

trait Enum
{

    private $value = null;

    public function __construct($value)
    {
        if (!in_array($value, $this->values())) {
            throw new SpeedyException('Invalid enum value for ' . get_class($this) . '.');
        }

        $this->value = $value;
    }

    public function values()
    {
        return (new ReflectionClass($this))->getConstants();
    }

    public function get()
    {
        return $this->value;
    }

}