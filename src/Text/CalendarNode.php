<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

 class CalendarNode extends TextNode {


    public function __construct(string $identifier, string | null $text = null)
    {
        $text = trim($text);

        if (! $this->isCalendarString($text)){
            throw new \RuntimeException('$text should be in the format of mm-dd-yyyy');
        }

        parent::__construct($identifier, $text);
    }

     public function isCalendarString(string $text): bool
     {
         $pattern = '/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/';
         preg_match($pattern, $text, $match);

         return isset($match[0]);
     }
}