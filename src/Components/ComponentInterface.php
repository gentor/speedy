<?php
namespace Gentor\Speedy\Components;

use SimpleXMLElement;

/**
 * Interface ComponentInterface
 * Defines serialization mechanisms for communication with Speedy end-points
 * @package Gentor\Speedy\Components
 * @version 1.0
 */
interface ComponentInterface
{

    /**
     * Returns serialization tag name, defaults to current class name if not overridden
     * @return string
     */
    public function tag();

    /**
     * Serializes the current instance into SimpleXMLElement
     * @return SimpleXMLElement
     */
    public function serialize();

    /**
     * Converts current instance to array
     * @return array
     */
    public function toArray();

}
