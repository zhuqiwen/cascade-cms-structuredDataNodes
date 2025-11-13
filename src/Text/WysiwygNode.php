<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

 use Edu\IU\RSB\StructuredDataNodes\NodeInterface;
 use PhpParser\Node;

 class WysiwygNode extends TextNode implements NodeInterface {


    public function __construct(string $identifier, string $text = '', bool $autoFixHtml = true)
    {
        $text = trim($text);
        $text = $autoFixHtml ? $this->closeAllTags($text) : $text;

        if (!$this->areAllTagsClosed($text)){
            throw new \RuntimeException("open tags and close tags in [$text] do not match");
        }
        parent::__construct($identifier, $text);
    }

     public function setValueText(string $val, bool $autoFixHtml = true):void
     {
         $text = trim($val);
         $text = $autoFixHtml ? $this->closeAllTags($text) : $text;
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

     public function closeAllTags(string $text): string
     {
         libxml_use_internal_errors(true);
         $doc = new \DOMDocument('1.0', 'UTF-8');

         // Add a wrapper so fragments parse correctly
         $doc->loadHTML(
             '<?xml encoding="utf-8" ?><div>' . $text . '</div>',
             LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
         );

         // Extract just the fragment inside our wrapper
         $output = '';
         foreach ($doc->documentElement->childNodes as $node) {
             $output .= $doc->saveHTML($node);
         }

         libxml_clear_errors();
         return trim($output);
     }
}