<?php

namespace Dook\Component\Form\Element;

class Password extends Text {

    public function __toString()
    {
        $this->delAttrib('value');
        return parent::__toString();
    }

    protected function init()
    {
        $this->setAttrib('type', 'password');
    }

}