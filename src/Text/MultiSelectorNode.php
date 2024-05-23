<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

 class MultiSelectorNode extends TextNode {


    public function __construct(string $identifier, array $options)
    {
        parent::__construct($identifier);
        $text = implode('::CONTENT-XML-SELECTOR::', $options);
        $this->nodeArray['text'] = $text;
    }
}