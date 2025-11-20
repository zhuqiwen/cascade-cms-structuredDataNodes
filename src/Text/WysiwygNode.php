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
         libxml_use_internal_errors(true);

         $doc = new \DOMDocument('1.0', 'UTF-8');

         // Wrap the HTML in a dummy element to handle fragments
         $ok = $doc->loadHTML(
             '<?xml encoding="utf-8" ?><div>' . $text . '</div>',
             LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
         );

         $errors = libxml_get_errors();
         libxml_clear_errors();

         // If parsing failed or libxml found errors, tags aren't all closed
         return $ok && count($errors) === 0;
     }

     public function closeAllTags(string $text): string
     {
         //rm all tags that star with '<system'
         $text = preg_replace('/<\/?system[^>]*>/i', '', $text);
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
             $output .= $doc->saveXML($node);
         }

         libxml_clear_errors();
         return trim($output);
     }
}