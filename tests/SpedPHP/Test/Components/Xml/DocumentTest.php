<?php

namespace SpedPHP\Test\Components\Xml;

use SpedPHP\Components\Xml\Document;

/**
 * @category   SpedPHP
 * @package    SpedPHP\Test\Components\Xml
 * @copyright  Copyright (c) 2012
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @author     Antonio Spinelli <tonicospinelli85@gmail.com>
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{

    public function testInstanceDocumentShouldWorks()
    {
        $document = new Document();
        $this->assertInstanceOf('DOMDocument', $document);
        $this->assertInstanceOf('SpedPHP\Components\Xml\Document', $document);
    }

    public function testCreateAndAppendChildInDocument()
    {
        $document = new Document();
        $node = $document->createElement('name', 'value');
        $document->appendChild($node);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<name>value</name>'
            . PHP_EOL;
        $this->assertEquals($xml, $document->saveXML());
    }

    public function testCreateAndAppendUniqueChildInDocument()
    {
        $document = new Document();
        $node = $document->createElement('name', 'value-01');
        $document->appendChild($node);
        $node = $document->createElement('name', 'value-02');
        $document->appendChild($node, true);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<name>value-02</name>'
            . PHP_EOL;
        $this->assertEquals($xml, $document->saveXML());
    }

    public function testRemoveElementsByTagNameInDocument()
    {
        $document = new Document();
        $node = $document->createElement('firstName', 'Tester');
        $document->appendChild($node);
        $node = $document->createElement('lastName', 'SpedPHP');
        $document->appendChild($node);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<firstName>Tester</firstName>'
            . PHP_EOL
            . '<lastName>SpedPHP</lastName>'
            . PHP_EOL;
        $this->assertEquals($xml, $document->saveXML());
        $document->removeElementsByTagName('firstName');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<lastName>SpedPHP</lastName>'
            . PHP_EOL;
        $this->assertEquals($xml, $document->saveXML());
    }

    public function testGetNamespacesInDocument()
    {
        $document = new Document();
        $document->formatOutput = true;
        $root = $document->createElementNS('http://www.w3.org/2005/Atom', 'element');
        $document->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:SpedPHP', 'http://nfephp.org/ns/1.0');
        $item = $document->createElementNS('http://nfephp.org/ns/1.0', 'SpedPHP:firstName', 'Tester');
        $root->appendChild($item);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<element xmlns="http://www.w3.org/2005/Atom" xmlns:SpedPHP="http://nfephp.org/ns/1.0">'
            . PHP_EOL
            . '  <SpedPHP:firstName>Tester</SpedPHP:firstName>'
            . PHP_EOL
            . '</element>'
            . PHP_EOL;
        $this->assertEquals($xml, $document->saveXML());
        $this->assertTrue($root->hasAttribute('xmlns:SpedPHP'));
        $this->assertEquals('SpedPHP', $item->prefix);
        $this->assertArrayHasKey('xmlns:SpedPHP', $document->getNamespaces());
        $namespaces = array(
            'xmlns:xml'  => 'http://www.w3.org/XML/1998/namespace',
            'xmlns:SpedPHP' => 'http://nfephp.org/ns/1.0',
        );
        $this->assertEquals($namespaces, $document->getNamespaces());
        $shortNamespaces = array(
            'xml'  => 'http://www.w3.org/XML/1998/namespace',
            'SpedPHP' => 'http://nfephp.org/ns/1.0',
        );
        $this->assertEquals($shortNamespaces, $document->getNamespaces(true));
    }

    public function testGetTargetNamespacesInDocument()
    {
        $document = new Document();
        $document->formatOutput = true;
        $root = $document->createElementNS('http://www.w3.org/2005/Atom', 'element');
        $document->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:SpedPHP', 'http://nfephp.org/ns/1.0');
        $root->setAttribute('targetNamespace', 'http://nfephp.org/ns/1.0');
        $item = $document->createElementNS('http://nfephp.org/ns/1.0', 'SpedPHP:firstName', 'Tester');
        $root->appendChild($item);

        $this->assertTrue($root->hasAttribute('xmlns:SpedPHP'));
        $this->assertEquals('http://nfephp.org/ns/1.0', $document->getTargetNamespace());
    }

    public function testIsParseQNameAndResolveNamespace()
    {
        $document = new Document();
        $document->formatOutput = true;
        $root = $document->createElementNS('http://www.w3.org/2005/Atom', 'element');
        $document->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:SpedPHP', 'http://nfephp.org/ns/1.0');
        $root->setAttribute('targetNamespace', 'http://nfephp.org/ns/1.0');
        $item = $document->createElementNS('http://nfephp.org/ns/1.0', 'SpedPHP:firstName', 'Tester');
        $root->appendChild($item);

        $this->assertTrue($document->isQName('xmlns:SpedPHP'));
        $this->assertEquals(array('xmlns', 'SpedPHP'), $document->parseQName('xmlns:SpedPHP'));
        $this->assertEquals(array('http://nfephp.org/ns/1.0', 'SpedPHP'), $document->parseQName('xmlns:SpedPHP', true));
    }

    public function testResolveNamespace()
    {
        $document = new Document();
        $document->formatOutput = true;
        $root = $document->createElementNS('http://www.w3.org/2005/Atom', 'element');
        $document->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:SpedPHP', 'http://nfephp.org/ns/1.0');
        $root->setAttribute('targetNamespace', 'http://nfephp.org/ns/1.0');
        $item = $document->createElementNS('http://nfephp.org/ns/1.0', 'SpedPHP:firstName', 'Tester');
        $root->appendChild($item);

        $this->assertEquals('http://nfephp.org/ns/1.0', $document->resolveNamespace('SpedPHP', true));
    }

    public function testGetNamespaceCode()
    {
        $document = new Document();
        $document->formatOutput = true;
        $root = $document->createElementNS('http://www.w3.org/2005/Atom', 'element');
        $document->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:SpedPHP', 'http://nfephp.org/ns/1.0');
        $root->setAttribute('targetNamespace', 'http://nfephp.org/ns/1.0');
        $item = $document->createElementNS('http://nfephp.org/ns/1.0', 'SpedPHP:firstName', 'Tester');
        $root->appendChild($item);

        $this->assertEquals('xmlns:SpedPHP', $document->getNamespaceCode('http://nfephp.org/ns/1.0'));
        $this->assertEquals('SpedPHP', $document->getNamespaceCode('http://nfephp.org/ns/1.0', true));
    }

    public function testArrayToXml()
    {
        $data = array(
            'book' => array(
                array(
                    'author'             => 'Author0',
                    'title'              => 'Title0',
                    'title'              => 'Title0',
                    Document::ATTRIBUTES => Array(
                        'isbn' => "978-3-16-148410-0"
                    )
                ),
                array(
                    'author'             => array('Author1', 'Author2'),
                    'title'              => 'Title0',
                    'title'              => 'Title0',
                    Document::ATTRIBUTES => Array(
                        'isbn' => "978-3-16-148410-0"
                    )
                ),
                array(
                    'author'             => 'Author3',
                    'title'              => array(
                        Document::CONTENT => 'Title0'
                    ),
                    Document::ATTRIBUTES => Array(
                        'isbn' => "978-3-16-148410-0"
                    )
                ),
            )
        );

        $document = Document::arrayToXml($data, 'books');
        $document->formatOutput = true;
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<books>'
            . PHP_EOL
            . '  <book isbn="978-3-16-148410-0">'
            . PHP_EOL
            . '    <author><![CDATA[Author0]]></author>'
            . PHP_EOL
            . '    <title><![CDATA[Title0]]></title>'
            . PHP_EOL
            . '  </book>'
            . PHP_EOL
            . '  <book isbn="978-3-16-148410-0">'
            . PHP_EOL
            . '    <author><![CDATA[Author1]]></author>'
            . PHP_EOL
            . '    <author><![CDATA[Author2]]></author>'
            . PHP_EOL
            . '    <title><![CDATA[Title0]]></title>'
            . PHP_EOL
            . '  </book>'
            . PHP_EOL
            . '  <book isbn="978-3-16-148410-0">'
            . PHP_EOL
            . '    <author><![CDATA[Author3]]></author>'
            . PHP_EOL
            . '    <title><![CDATA[Title0]]></title>'
            . PHP_EOL
            . '  </book>'
            . PHP_EOL
            . '</books>'
            . PHP_EOL;
        $this->assertEquals($xml, $document->saveXML());
    }

    public function testXmlDocumentToArray()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<books>'
            . PHP_EOL
            . '  <book isbn="978-3-16-148410-0">'
            . PHP_EOL
            . '    <author><![CDATA[Author0]]></author>'
            . PHP_EOL
            . '    <title><![CDATA[Title0]]></title>'
            . PHP_EOL
            . '  </book>'
            . PHP_EOL
            . '  <book isbn="978-3-16-148410-0">'
            . PHP_EOL
            . '    <author><![CDATA[Author1]]></author>'
            . PHP_EOL
            . '    <author><![CDATA[Author2]]></author>'
            . PHP_EOL
            . '    <title><![CDATA[Title0]]></title>'
            . PHP_EOL
            . '  </book>'
            . PHP_EOL
            . '  <book isbn="978-3-16-148410-0">'
            . PHP_EOL
            . '    <author><![CDATA[Author3]]></author>'
            . PHP_EOL
            . '    <title><![CDATA[Title0]]></title>'
            . PHP_EOL
            . '  </book>'
            . PHP_EOL
            . '</books>'
            . PHP_EOL;
        $document = new Document();
        $document->loadXML($xml);
        $document->formatOutput = true;

        $data = array(
            'book' => array(
                array(
                    Document::ATTRIBUTES => Array(
                        'isbn' => "978-3-16-148410-0"
                    ),
                    'author'             => array(
                        array(Document::CONTENT => 'Author0')
                    ),
                    'title'              => array(
                        array(Document::CONTENT => 'Title0')
                    ),
                ),
                array(
                    Document::ATTRIBUTES => Array(
                        'isbn' => "978-3-16-148410-0"
                    ),
                    'author'             => array(
                        array(Document::CONTENT => 'Author1'),
                        array(Document::CONTENT => 'Author2'),
                    ),
                    'title'              => array(
                        array(Document::CONTENT => 'Title0')
                    ),
                ),
                array(
                    Document::ATTRIBUTES => Array(
                        'isbn' => "978-3-16-148410-0"
                    ),
                    'author'             => array(
                        array(Document::CONTENT => 'Author3'),
                    ),
                    'title'              => array(
                        array(Document::CONTENT => 'Title0')
                    ),
                ),
            )
        );

        $this->assertEquals($data, Document::xmlDocumentToArray($document));
    }
}
