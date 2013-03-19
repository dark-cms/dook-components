<?php

namespace Dook\Component\Form\Element;

class Hidden extends Base {

    public function __toString()
    {
        $attribs = call_user_func_array([$this, 'renderAttribs'], func_get_args());
        return sprintf('<input %s />', $attribs);
    }

    protected function init()
    {
        $this->setAttrib('type', 'hidden');
    }

}