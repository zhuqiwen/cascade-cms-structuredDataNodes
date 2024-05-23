<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

 class CheckboxNode extends TextNode {


    public function __construct(string $identifier, array $options)
    {
        $text = implode('::CONTENT-XML-CHECKBOX::', $options);
        parent::__construct($identifier);
        $this->nodeArray['text'] = $text;
    }
}