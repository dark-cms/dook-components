<?php

namespace Dook\Component\Form\Element;

class Radio extends Checkbox {

    public function init()
    {
        parent::init();
        $this->setAttrib('type', 'radio');
    }

}