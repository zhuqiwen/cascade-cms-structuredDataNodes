<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Text;

 use Edu\IU\RSB\StructuredDataNodes\NodeInterface;
 use PhpParser\Node;

 class WysiwygNode extends TextNode implements NodeInterface {


    public function __construct(string $identifier, string $text = '', bool $autoFixHtml = true)
    {
        $text = trim($text);
        $text = $autoFixHtml ? $this->fixInput($text) : $text;

        if (!$this->isValid($text)){
            throw new \RuntimeException("open tags and close tags in [$text] do not match");
        }
        parent::__construct($identifier, $text);
    }

     public function setValueText(string $val, bool $autoFixHtml = true):void
     {
         $text = trim($val);
         $text = $autoFixHtml ? $this->fixInput($text) : $text;
         if (!$this->isValid($text)){
             throw new \RuntimeException('open tags and close tags in $text do not match');
         }
         $this->text = $text;
    }

     public function isValid(string $text):bool
     {
         $config = [
             'output-xhtml' => true,
             'show-body-only' => true,
             'clean' => true,
             'wrap' => false,
         ];
         $tidy = new \tidy();
         $tidy->parseString($text, $config, 'utf8');
         $errors = tidy_get_error_buffer($tidy);


         return !$errors === true;
     }

     public function fixInput(string $text): string | \tidy
     {
         $config = [
             'output-xhtml' => true,
             'show-body-only' => true,
             'clean' => true,
             'wrap' => false,
         ];
         $tidy = new \tidy();
         $tidy->parseString($text, $config, 'utf8');
         $tidy->cleanRepair();

         return $tidy;
     }
}