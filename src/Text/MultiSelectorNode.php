<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

 class MultiSelectorNode extends TextNode {


    public function __construct(string $identifier, array $options = [])
    {
        $text = implode('::CONTENT-XML-SELECTOR::', $options);
        parent::__construct($identifier, $text);
    }

     public function setMultiSelectorValue(array $options):void
     {
         $this->text = implode('::CONTENT-XML-SELECTOR::', $options);
     }
}