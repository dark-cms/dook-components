<?php

namespace Dook\Component\Form\Element;

class Textarea extends Base {

    /**
     * Store the element content value
     * @var string
     */
    protected $_content = null;

    public function __toString()
    {
        if ($this->hasAttrib('value') && $this->getContent() === null) {
            $this->setContent($this->getAttrib('value'));
            $this->delAttrib('value');
        }

        $attribs = call_user_func_array([$this, 'renderAttribs'], func_get_args());

        return sprintf('<textarea %s>%s</textarea>', $attribs, $this->getContent());
    }

    protected function init()
    {
        
    }

    /**
     * Set the textarea content
     * @param mixed $content Textarea content
     * @return \Dook\Component\Form\Element\Textarea
     */
    public function setContent($content)
    {
        $this->_content = $content;
        return $this;
    }

    /**
     * Get the textarea content
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

}