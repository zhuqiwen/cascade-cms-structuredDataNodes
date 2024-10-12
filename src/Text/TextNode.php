<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

use Edu\IU\RSB\StructuredDataNodes\BaseNode;
use Edu\IU\RSB\StructuredDataNodes\NodeInterface;

abstract class TextNode extends BaseNode implements NodeInterface {


    public function __construct(string $identifier, string  | null $text = null)
    {
        parent::__construct('text', $identifier);
        $this->text = $text;
    }


    public function setValueText(string $val):void
    {
        $this->text = $val;
    }
}