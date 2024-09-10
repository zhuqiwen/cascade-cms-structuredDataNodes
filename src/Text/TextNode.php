<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

use Edu\IU\RSB\StructuredDataNodes\BaseNode;
abstract class TextNode extends BaseNode{


    public function __construct(string $identifier, string $text = '')
    {
        parent::__construct('text', $identifier);
        $this->text = $text;
    }


    public function setValueText(string $val):void
    {
        $this->text = $val;
    }
}