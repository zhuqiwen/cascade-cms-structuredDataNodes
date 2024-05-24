<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

 class DataTimeNode extends TextNode {


    public function __construct(string $identifier, string $text)
    {
        $text = trim($text);

        if(!$this->isEpochFormat($text)){
            throw new \RuntimeException('$text must be an eligible epoch string consisting of 13 numeric characters');
        }

        parent::__construct($identifier, $text);
    }

     public function isEpochFormat(string $text): bool
     {
         $pattern = '/^[0-9]{13}$/';
         preg_match($pattern, $text, $match);

         return isset($match[0]);
     }
}