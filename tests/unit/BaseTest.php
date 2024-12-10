<?php

namespace unit;

use Edu\IU\RSB\StructuredDataNodes\Converter;
use Edu\IU\RSB\StructuredDataNodes\SystemDataStructureRoot;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase{
    protected static string $mockDataIETPath = __DIR__ . '/../mockData/IET-v2.json';
    protected static string $mockDataIWFPath = __DIR__ . '/../mockData/IU-FRAMEWORK.json';

    protected static array $mockDataIET;
    protected static  array $mockDataIWF;
    protected static Converter $converter;

    protected static SystemDataStructureRoot $systemDataStructureRoot;

    public static function setUpBeforeClass(): void
    {
        self::$converter = new Converter();
        self::$systemDataStructureRoot = new SystemDataStructureRoot();
        self::$mockDataIET = self::$converter->convert(json_decode(file_get_contents(self::$mockDataIETPath)));
        self::$mockDataIWF = self::$converter->convert(json_decode(file_get_contents(self::$mockDataIWFPath)));
    }
}