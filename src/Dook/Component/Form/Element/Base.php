<?php

namespace Dook\Component\Form\Element;

abstract class Base implements IRenderable, \Countable, \IteratorAggregate {

    /**
     * Store the element attributes
     * @var array
     */
    protected $_attribs = [];

    /**
     * Constructor (Will call: setAttrib() for 'id' and 'name', preInit(), init() and postInit())
     * @param string $name Element name (will also set this value as the element ID)
     */
    public function __construct($name)
    {
        $this->setAttrib('id', $name);
        $this->setAttrib('name', $name);
        $this->preInit();
        $this->init();
        $this->postInit();
    }

    /**
     * Override this method to execute anything before the initialization
     */
    protected function preInit()
    {
        
    }

    /**
     * Override this method to execute anything after the initialization
     */
    protected function postInit()
    {
        
    }

    /**
     * Initialize the element
     */
    abstract protected function init();

    /**
     * Set an attribute value
     * @param string $name Attribute name
     * @param mixed $value Attribute value
     * @return \Dook\Component\Form\Element\Base
     */
    public function setAttrib($name, $value)
    {
        $this->_attribs[$name] = $value;
        return $this;
    }

    /**
     * Get the value of an attribute
     * @param string $name Attribute name
     * @return mixed Returns the attribute value or null if not defined
     */
    public function getAttrib($name)
    {
        return ($this->hasAttrib($name)) ? $this->_attribs[$name] : null;
    }

    /**
     * Check if an attribute exists
     * @param string $name Attribute name
     * @return bool
     */
    public function hasAttrib($name)
    {
        return array_key_exists($name, $this->_attribs);
    }

    /**
     * Remove an attribute (if exists)
     * @param string $name Attribute name
     * @return \Dook\Component\Form\Element\Base
     */
    public function delAttrib($name)
    {
        if ($this->hasAttrib($name)) {
            unset($this->_attribs[$name]);
        }

        return $this;
    }

    /**
     * Render the element attributes
     * @param bool $includeEmpty Render empty attributes?
     * @return string
     */
    protected function renderAttribs($includeEmpty = false)
    {
        $out = [];

        foreach ($this->_attribs as $key => $value) {
            if (strlen($value) === 0 && $includeEmpty !== true) {
                continue;
            }

            $out[] = sprintf('%s="%s"', $key, $value);
        }

        return implode(' ', $out);
    }

    /**
     * Number of defined attributes
     * @return int
     */
    public function count()
    {
        return count($this->_attribs);
    }

    /**
     * Allows to iterate element attributes on loops
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_attribs);
    }

}