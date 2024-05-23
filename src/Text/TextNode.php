<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

use Edu\IU\RSB\StructuredDataNodes\BaseNode;
abstract class TextNode extends BaseNode{


    public function __construct(string $identifier)
    {
        parent::__construct('text', $identifier);
    }
}