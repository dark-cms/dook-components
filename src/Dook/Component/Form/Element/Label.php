<?php

namespace Dook\Component\Form\Element;

class Label extends Base {

    /**
     * Label caption
     * @var string
     */
    protected $_label = null;

    public function __construct($label)
    {
        $this->setLabel($label);
        $this->preInit();
        $this->init();
        $this->postInit();
    }

    public function __toString()
    {
        if ($this->getLabel() instanceof \Dook\Component\Form\Element\IRenderable) {
            $label = (string) $this->getLabel();
        } else {
            $label = htmlentities($this->getLabel(), \ENT_QUOTES, 'UTF-8', false);
        }

        $attribs = call_user_func_array([$this, 'renderAttribs'], func_get_args());
        return sprintf('<label %s>%s</label>', $attribs, $label);
    }

    protected function init()
    {
        
    }

    /**
     * Set the label caption
     * @param string|\Dook\Component\Form\Element\IRenderable $label Label caption (as show to the user)
     * @return \Dook\Component\Form\Element\Label
     */
    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }

    /**
     * Get the label caption
     * @return mixed Can be a string or an instance of an object (like \Dook\Component\Form\Element\IRenderable)
     */
    public function getLabel()
    {
        return $this->_label;
    }

}