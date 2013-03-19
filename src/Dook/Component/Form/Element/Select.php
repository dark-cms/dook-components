<?php

namespace Dook\Component\Form\Element;

class Select extends Base {

    /**
     * Options to populate the select
     * @var array
     */
    protected $_options = [];

    /**
     * Values to be selected
     * @var array
     */
    protected $_values = [];

    /**
     * Indicate if the options passed is an array of arrays or an array of objects
     * @var bool
     */
    protected $_optionsAsArray = true;

    /**
     * The keys to be used when rendering the options
     * @var array
     */
    protected $_optionsKeys = [
        'key'   => 'key',
        'value' => 'value'
    ];

    public function __toString()
    {
        if ($this->hasAttrib('value') && count($this->getValues()) === 0) {
            $value = $this->getAttrib('value');
            $this->delAttrib('value');
            $this->setValues(is_array($value) ? $value : [$value]);
        }

        $attribs = call_user_func_array([$this, 'renderAttribs'], func_get_args());
        return sprintf('<select %s>%s</select>', $attribs, $this->renderOptions());
    }

    /**
     * Render the select options
     * @return string
     */
    protected function renderOptions()
    {
        $out     = [];
        $keys    = $this->getOptionsKeys();
        $kKey    = $keys['key'];
        $kVal    = $keys['value'];
        $isArray = $this->_optionsAsArray;
        $options = $this->getOptions();
        $values  = $this->getValues();

        if ($isArray) {
            foreach ($options as $o) {
                $s     = (in_array($o[$kKey], $values)) ? ' selected="selected"' : '';
                $label = htmlentities($o[$kVal], \ENT_QUOTES, 'UTF-8', false);
                $out[] = sprintf('<option value="%s"%s>%s</option>', $o[$kKey], $s, $label);
            }
        } else {
            foreach ($options as $o) {
                $s     = (in_array($o->$kKey, $values)) ? ' selected="selected"' : '';
                $label = htmlentities($o->$kVal, \ENT_QUOTES, 'UTF-8', false);
                $out[] = sprintf('<option value="%s"%s>%s</option>', $o->$kKey, $s, $label);
            }
        }

        return implode("\n", $out);
    }

    protected function init()
    {
        
    }

    /**
     * Set the select options
     * @param array $options Options to be used to populate the select
     * @param bool $arrayOfArrays Indicate if the options is an array of arrays or an array of objects
     * @return \Dook\Component\Form\Element\Select
     */
    public function setOptions(array $options, $arrayOfArrays = true)
    {
        $this->_options        = $options;
        $this->_optionsAsArray = $arrayOfArrays;
        return $this;
    }

    /**
     * Get the select options
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Set the value to be selected
     * @param array $values Values to be selected
     * @return \Dook\Component\Form\Element\Select
     */
    public function setValues(array $values)
    {
        $this->_values = $values;
        return $this;
    }

    /**
     * Get the values to be selected
     * @return array
     */
    public function getValues()
    {
        return $this->_values;
    }

    /**
     * Set the name of the key (for the option value and label) when rendering the options values
     * @param string $keyForKey Key for the option value (value attribute)
     * @param string $keyForValue Key for the option label (as show to the user)
     * @return \Dook\Component\Form\Element\Select
     */
    public function setOptionsKeys($keyForKey, $keyForValue)
    {
        $this->_optionsKeys['key']   = $keyForKey;
        $this->_optionsKeys['value'] = $keyForValue;
        return $this;
    }

    /**
     * Get the keys of options rendering
     * @return array
     */
    public function getOptionsKeys()
    {
        return $this->_optionsKeys;
    }

}