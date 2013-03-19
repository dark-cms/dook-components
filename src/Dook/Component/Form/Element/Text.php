<?php

namespace Dook\Component\Form\Element;

class Text extends Hidden {

    protected function init()
    {
        $this->setAttrib('type', 'text');
    }

}