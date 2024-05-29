<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

 class WysiwygNode extends TextNode {


    public function __construct(string $identifier, string $text)
    {
        $text = trim($text);
        if (!$this->areAllTagsClosed($text)){
            throw new \RuntimeException('open tags and close tags in $text do not match');
        }
        parent::__construct($identifier, $text);
    }

     public function setValueText(string $val):void
     {
         $text = trim($val);
         if (!$this->areAllTagsClosed($text)){
             throw new \RuntimeException('open tags and close tags in $text do not match');
         }
         $this->text = $text;
    }

     public function areAllTagsClosed(string $text):bool
     {
         $openTagPattern = '#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU';
         $closeTagPattern = '#</([a-z]+)>#iU';
         preg_match_all($openTagPattern, $text, $openMatches);
         preg_match_all($closeTagPattern, $text, $closeMatches);

         // no tags
         if (empty($openMatches) && empty($closeMatches)){
             return true;
         }else{
             // open tags exists but no close tags
             // or close tags exists but no open tags
             if (sizeof($openMatches) != sizeof($closeMatches)){
                 return false;
             }else{
                 // there are open tags and close tags,
                 // then compare the number of them
                return sizeof($openMatches[1]) == sizeof($closeMatches[1]);
             }
         }
     }
}