<?php

namespace unit;


use Edu\IU\RSB\StructuredDataNodes\BaseNode;
use Edu\IU\RSB\StructuredDataNodes\Converter;
use Edu\IU\RSB\StructuredDataNodes\GroupNode;
use Edu\IU\RSB\StructuredDataNodes\SystemDataStructureRoot;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

class GroupNodeTest extends TestCase
{
    private string $mockDataIETPath = __DIR__ . '/../mockData/IET-v2.json';
    private string $mockDataIWFPath = __DIR__ . '/../mockData/IU-FRAMEWORK.json';

    private array $mockDataIET;
    private array $mockDataIWF;
    private Converter $converter;


    protected function setUp(): void
    {
        $this->converter = new Converter();
        $this->mockDataIET = $this->converter->convert(json_decode(file_get_contents($this->mockDataIETPath)));
        $this->mockDataIWF = $this->converter->convert(json_decode(file_get_contents($this->mockDataIWFPath)));
    }

    private function getClassNameWithoutNamespace(BaseNode $node):string
    {
        return basename(str_replace('\\', '/', get_class($node)));

    }

    private function isGroupNode(BaseNode $node):bool
    {
        return $this->getClassNameWithoutNamespace($node) == 'GroupNode';
    }

    public function testGetAllDescendantNodesByPathReturnsEmptyArrayForEmptyPath()
    {
        foreach ($this->mockDataIET as $node) {
            if ($this->isGroupNode($node)){
                $result = $node->getAllDescendantNodesByPath('');
                $this->assertSame([], $result);
            }
        }

        foreach ($this->mockDataIWF as $node) {
            if ($this->isGroupNode($node)){
                $result = $node->getAllDescendantNodesByPath('');
                $this->assertSame([], $result);
            }
        }
    }

    public function testGetAllDescendantNodesByPathReturnsArrayWithExpectedSize()
    {
        //IET framework data
        $sectionGroupNode = $this->mockDataIET[3];
        $chunksArray = $sectionGroupNode->getAllDescendantNodesByPath('column/chunk');
        $this->assertSame(4, sizeof($chunksArray));

        $sectionGroupNode = $this->mockDataIET[4];
        $chunksArray = $sectionGroupNode->getAllDescendantNodesByPath('column/chunk');
        $this->assertSame(1, sizeof($chunksArray));

        //IU web framework data
        $sectionGroupNode = $this->mockDataIWF[3];
        $foldsArray = $sectionGroupNode->getAllDescendantNodesByPath('chunk/details/fold');
        $this->assertSame(3, sizeof($foldsArray));

        $sectionGroupNode = $this->mockDataIWF[4];
        $foldsArray = $sectionGroupNode->getAllDescendantNodesByPath('chunk/details/fold');
        $this->assertSame(7, sizeof($foldsArray));
    }

    public function testGetAllDescendantNodesByPathReturnsChildren()
    {
        $socialMediaGroupNode = $this->mockDataIET[1];
        $useSocialMediaMeta = $socialMediaGroupNode->getAllDescendantNodesByPath('share/use');
        $this->assertSame(1, sizeof($useSocialMediaMeta));
        $useSocialMediaMeta = $useSocialMediaMeta[0];
        $this->assertSame('::CONTENT-XML-CHECKBOX::Yes', $useSocialMediaMeta->text);

        $bannerGroupNode = $this->mockDataIET[2];
        $linkInternalNodesArray = $bannerGroupNode->getAllDescendantNodesByPath('link-internal');
        $this->assertSame(1, sizeof($linkInternalNodesArray));
        $linkInternalNode = $linkInternalNodesArray[0];
        $this->assertSame('page,file,symlink', $linkInternalNode->assetType);
        $this->assertSame('37e6ce4a814f4e103b8fcdaf255ea742', $linkInternalNode->pageId);
        $this->assertSame('index', $linkInternalNode->pagePath);


        $sectionGroupNode = $this->mockDataIET[3];
        $chunkTypeNodesArray = $sectionGroupNode->getAllDescendantNodesByPath('column/chunk/type');
        $this->assertSame(4, sizeof($chunkTypeNodesArray));
        $expectedTypes = ['Accordion', 'Table', 'Text', 'Table'];
        $types = [];
        foreach ($chunkTypeNodesArray as $chunkTypeNode) {
            $types[] = $chunkTypeNode->text;
        }
        $this->assertSame($expectedTypes, $types);

        $backgroundImageNodesArray = $sectionGroupNode->getAllDescendantNodesByPath('background/bg-image');
        $this->assertSame(1, sizeof($backgroundImageNodesArray));
        $backgroundImageNode = $backgroundImageNodesArray[0];
        $this->assertSame('file', $backgroundImageNode->assetType);
        $this->assertSame('e7ca9c39814f4e1076cbe080d017ea68', $backgroundImageNode->fileId);
        $this->assertSame('old-site/images/slider-primary-img-2-1.jpg', $backgroundImageNode->filePath);


        $bannerGroupNode = $this->mockDataIWF[2];
        $bgColorNodesArray = $bannerGroupNode->getAllDescendantNodesByPath('background/bg-color');
        $this->assertSame(1, sizeof($bgColorNodesArray));
        $bgColorNode = $bgColorNodesArray[0];
        $this->assertSame('Light Gray', $bgColorNode->text);

        $sectionGroupNode = $this->mockDataIWF[3];
        $chunkTypeNodesArray = $sectionGroupNode->getAllDescendantNodesByPath('chunk/type');
        $this->assertSame(1, sizeof($chunkTypeNodesArray));
        $chunkTypeNode = $chunkTypeNodesArray[0];
        $this->assertSame('Accordion', $chunkTypeNode->text);
    }


}
