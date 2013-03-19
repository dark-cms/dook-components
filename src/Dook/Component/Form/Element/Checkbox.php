<?php

namespace Dook\Component\Form\Element;

class Checkbox extends Base {

    /**
     * Checkbox label
     * @var string
     */
    protected $_label = null;

    /**
     * Store the value to compare and check if the element should be marked as checked
     * @var mixed
     */
    protected $_checked = null;

    public function __toString()
    {
        $checked = $this->getChecked();

        if ($checked !== null && $this->hasAttrib('value')) {

            $value = $this->getAttrib('value');

            if ($checked === true || (strlen($value) > 0 && $checked == $value)) {
                $this->setAttrib('checked', 'checked');
            }
        }

        $label   = htmlentities($this->getLabel(), \ENT_QUOTES, 'UTF-8', false);
        $attribs = call_user_func_array([$this, 'renderAttribs'], func_get_args());
        return sprintf('<input %s /> %s', $attribs, $label);
    }

    protected function init()
    {
        $this->setAttrib('type', 'checkbox');
    }

    /**
     * Set the checkbox label
     * @param string $label Checkbox label (as show to the user)
     * @return \Dook\Component\Form\Element\Checkbox
     */
    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }

    /**
     * Get the checkbox label
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * Set the value to compare and check if the element should be marked as checked
     * @param mixed $value Value to compare with the element VALUE attribute
     * @return \Dook\Component\Form\Element\Checkbox
     */
    public function setChecked($value)
    {
        $this->_checked = $value;
        return $this;
    }

    /**
     * Get the value to be used to compare for marking the component as checked
     * @return mixed
     */
    public function getChecked()
    {
        return $this->_checked;
    }

}