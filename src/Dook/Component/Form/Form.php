<?php

namespace Dook\Component\Form;

use Dook\Component\Form\Element\Hidden;
use Dook\Component\Form\Element\Text;
use Dook\Component\Form\Element\Password;
use Dook\Component\Form\Element\Textarea;
use Dook\Component\Form\Element\Checkbox;
use Dook\Component\Form\Element\Radio;
use Dook\Component\Form\Element\Label;
use Dook\Component\Form\Element\Select;

class Form implements \Countable, \IteratorAggregate {

    /**
     * Store the form elements
     * @var array
     */
    protected $_elements = [];

    /**
     * Constructor (Calls in this order: preInit(), init(), postInit())
     */
    public function __construct()
    {
        $this->preInit();
        $this->init();
        $this->postInit();
    }

    /**
     * Set an element to be stored by the form
     * @param string $name Element name for form referencing (don't need to be the same name / id as the element object)
     * @param \Dook\Component\Form\Element\IRenderable $value Element instance
     */
    public function __set($name, $value)
    {
        $this->_elements[$name] = $value;
    }

    /**
     * Get a stored element from the form
     * @param string $name Element name for form referencing (don't need to be the same name / id as the element object)
     * @return \Dook\Component\Form\Element\IRenderable
     */
    public function __get($name)
    {
        return isset($this->$name) ? $this->_elements[$name] : null;
    }

    /**
     * Check if an element exists on the form
     * @param string $name Element name for form referencing (don't need to be the same name / id as the element object)
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_elements[$name]);
    }

    /**
     * Remove an element from the form
     * @param string $name Element name for form referencing (don't need to be the same name / id as the element object)
     */
    public function __unset($name)
    {
        if (isset($this->$name)) {
            unset($this->_elements[$name]);
        }
    }

    /**
     * Render the form
     * @return string
     */
    public function __toString()
    {
        $out = [];
        $sep = "\n";

        $args = (func_num_args() > 0) ? func_get_arg(0) : [];

        foreach ($this->_elements as $e) {
            $out[] = call_user_func_array([$e, '__toString'], $args);
        }

        if (func_num_args() > 1) {
            $sep = func_get_arg(1);
        }

        return implode($sep, $out);
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
     * Override this method to initialize the form elements
     */
    protected function init()
    {
        
    }

    /**
     * Create an element of type HIDDEN
     * @param string $name Element name
     * @param mixed $value Element value
     * @return \Dook\Component\Form\Element\Hidden
     */
    public function hidden($name, $value = null)
    {
        $o = new Hidden($name);
        $o->setAttrib('value', $value);
        return $o;
    }

    /**
     * Create an element of type TEXT
     * @param string $name Element name
     * @param mixed $value Element value
     * @return \Dook\Component\Form\Element\Text
     */
    public function text($name, $value = null)
    {
        $o = new Text($name);
        $o->setAttrib('value', $value);
        return $o;
    }

    /**
     * Create an element of type PASSWORD
     * @param string $name Element name
     * @return \Dook\Component\Form\Element\Password
     */
    public function password($name)
    {
        return new Password($name);
    }

    /**
     * Create an element of type TEXTAREA
     * @param string $name Element name
     * @param mixed $value Element value (in this case the textarea content)
     * @return \Dook\Component\Form\Element\Textarea
     */
    public function textarea($name, $value = null)
    {
        $o = new Textarea($name);
        $o->setContent($value);
        return $o;
    }

    /**
     * Create an element of type LABEL
     * @param string|\Dook\Component\Form\Element\IRenderable $label Label caption or a component to be rendered insted of a caption
     * @param string $forId Set the FOR attribute (the element that the label references)
     * @return \Dook\Component\Form\Element\Label
     */
    public function label($label, $forId = null)
    {
        $o = new Label($label);
        $o->setAttrib('for', $forId);
        return $o;
    }

    /**
     * Create an element of type CHECKBOX
     * @param string $name Element name
     * @param mixed $value Element value
     * @param mixed $checked Value to be used to compare with the element VALUE attribute to mark the element as checked
     * @return \Dook\Component\Form\Element\Checkbox
     */
    public function checkbox($name, $value = null, $checked = null)
    {
        $o = new Checkbox($name);
        $o->setAttrib('value', $value);
        $o->setChecked($checked);
        return $o;
    }

    /**
     * Create an element of type RADIO
     * @param string $name Element name
     * @param mixed $value Element value
     * @param mixed $checked Value to be used to compare with the element VALUE attribute to mark the element as checked
     * @return \Dook\Component\Form\Element\Radio
     */
    public function radio($name, $value = null, $checked = null)
    {
        $o = new Radio($name);
        $o->setAttrib('value', $value);
        $o->setChecked($checked);
        return $o;
    }

    /**
     * Create an element of type SELECT
     * @param string $name Element name
     * @param array $options Array to be used to populate (fill) the select with OPTIONS
     * @param array $value Array with the value(s) to be selected
     * @return \Dook\Component\Form\Element\Select
     */
    public function select($name, array $options, array $value = [])
    {
        $o = new Select($name);
        $o->setOptions($options);
        $o->setValues($value);
        return $o;
    }

    /**
     * Executed before populate the elements (override this when needed)
     * @param array $data
     */
    protected function prePopulate(array $data)
    {
        
    }

    /**
     * Executed after populate the elements (override this when needed)
     * @param array $data
     */
    protected function postPopulate(array $data)
    {
        
    }

    /**
     * Populate the form elements with data (usually from POST requests)
     * @param array $data Data to be used to fill the form elements
     * @return \Dook\Component\Form\Form
     */
    public function populate(array $data)
    {
        $this->prePopulate($data);

        foreach ($this->_elements as $e) {

            if ($e instanceof Label) {
                continue;
            }

            if (array_key_exists($e->getAttrib('name'), $data)) {
                $this->populateElement($e, $data[$e->getAttrib('name')]);
                continue;
            }

            if (array_key_exists($e->getAttrib('id'), $data)) {
                $this->populateElement($e, $data[$e->getAttrib('id')]);
            }
        }

        $this->postPopulate($data);

        return $this;
    }

    /**
     * Simple check the element type and call the apropriate method to populate the element
     * @param \Dook\Component\Form\Element\IRenderable $element Element to be populated (filled)
     * @param mixed $value Value to fill the element (or set it's content or mark it as selected / checked)
     * @return mixed
     */
    protected function populateElement(\Dook\Component\Form\Element\IRenderable $element, $value)
    {
        if ($element instanceof Password) {
            return;
        }

        if ($element instanceof Hidden) {
            return $element->setAttrib('value', $value);
        }

        if ($element instanceof Checkbox) {
            return $element->setChecked($value);
        }

        if ($element instanceof Textarea) {
            return $element->setContent($value);
        }

        if ($element instanceof Select) {
            return $element->setValues(is_array($value) ? $value : [$value]);
        }
    }

    /**
     * Number of defined form elements
     * @return int
     */
    public function count()
    {
        return count($this->_elements);
    }

    /**
     * Allows to iterate form elements on loops
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_elements);
    }

}