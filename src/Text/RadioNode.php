<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

 class RadioNode extends TextNode {


    public function __construct(string $identifier, string $text)
    {
        parent::__construct($identifier);
        $this->nodeArray['text'] = $text;
    }
}