<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

 use Edu\IU\RSB\StructuredDataNodes\NodeInterface;

 class CheckboxNode extends TextNode implements NodeInterface {


    public function __construct(string $identifier, array $options = [])
    {
        $text = implode('::CONTENT-XML-CHECKBOX::', $options);
        parent::__construct($identifier, $text);
    }

     public function setCheckboxValue(array $options):void
     {
         $this->text = implode('::CONTENT-XML-CHECKBOX::', $options);
     }
}