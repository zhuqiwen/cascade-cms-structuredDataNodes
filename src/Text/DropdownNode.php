<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

 class DropdownNode extends TextNode {


    public function __construct(string $identifier, string $text)
    {
        parent::__construct($identifier);
        $this->nodeArray['text'] = $text;
    }
}