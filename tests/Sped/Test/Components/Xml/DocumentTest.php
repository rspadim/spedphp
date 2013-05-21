<?php

/**
 * Sped
 *
 * Copyright (c) 2012 Sped
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   Sped
 * @package    Sped
 * @copyright  Copyright (c) 2012 Sped (https://github.com/tonicospinelli/Sped)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */

namespace Sped\Test\Components\Xml;

use Sped\Components\Xml\Document;

/**
 * @category   Sped
 * @package    Sped\Components\Xml
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
        $this->assertInstanceOf('Sped\Components\Xml\Document', $document);
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
        $node = $document->createElement('lastName', 'Sped');
        $document->appendChild($node);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<firstName>Tester</firstName>'
            . PHP_EOL
            . '<lastName>Sped</lastName>'
            . PHP_EOL;
        $this->assertEquals($xml, $document->saveXML());
        $document->removeElementsByTagName('firstName');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<lastName>Sped</lastName>'
            . PHP_EOL;
        $this->assertEquals($xml, $document->saveXML());
    }

    public function testGetNamespacesInDocument()
    {
        $document = new Document();
        $document->formatOutput = true;
        $root = $document->createElementNS('http://www.w3.org/2005/Atom', 'element');
        $document->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sped', 'http://nfephp.org/ns/1.0');
        $item = $document->createElementNS('http://nfephp.org/ns/1.0', 'sped:firstName', 'Tester');
        $root->appendChild($item);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<element xmlns="http://www.w3.org/2005/Atom" xmlns:sped="http://nfephp.org/ns/1.0">'
            . PHP_EOL
            . '  <sped:firstName>Tester</sped:firstName>'
            . PHP_EOL
            . '</element>'
            . PHP_EOL;
        $this->assertEquals($xml, $document->saveXML());
        $this->assertTrue($root->hasAttribute('xmlns:sped'));
        $this->assertEquals('sped', $item->prefix);
        $this->assertArrayHasKey('xmlns:sped', $document->getNamespaces());
        $namespaces = array(
            'xmlns:xml'  => 'http://www.w3.org/XML/1998/namespace',
            'xmlns:sped' => 'http://nfephp.org/ns/1.0',
        );
        $this->assertEquals($namespaces, $document->getNamespaces());
        $shortNamespaces = array(
            'xml'  => 'http://www.w3.org/XML/1998/namespace',
            'sped' => 'http://nfephp.org/ns/1.0',
        );
        $this->assertEquals($shortNamespaces, $document->getNamespaces(true));
    }

    public function testGetTargetNamespacesInDocument()
    {
        $document = new Document();
        $document->formatOutput = true;
        $root = $document->createElementNS('http://www.w3.org/2005/Atom', 'element');
        $document->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sped', 'http://nfephp.org/ns/1.0');
        $root->setAttribute('targetNamespace', 'http://nfephp.org/ns/1.0');
        $item = $document->createElementNS('http://nfephp.org/ns/1.0', 'sped:firstName', 'Tester');
        $root->appendChild($item);

        $this->assertTrue($root->hasAttribute('xmlns:sped'));
        $this->assertEquals('http://nfephp.org/ns/1.0', $document->getTargetNamespace());
    }

    public function testIsParseQNameAndResolveNamespace()
    {
        $document = new Document();
        $document->formatOutput = true;
        $root = $document->createElementNS('http://www.w3.org/2005/Atom', 'element');
        $document->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sped', 'http://nfephp.org/ns/1.0');
        $root->setAttribute('targetNamespace', 'http://nfephp.org/ns/1.0');
        $item = $document->createElementNS('http://nfephp.org/ns/1.0', 'sped:firstName', 'Tester');
        $root->appendChild($item);

        $this->assertTrue($document->isQName('xmlns:sped'));
        $this->assertEquals(array('xmlns', 'sped'), $document->parseQName('xmlns:sped'));
        $this->assertEquals(array('http://nfephp.org/ns/1.0', 'sped'), $document->parseQName('xmlns:sped', true));
    }

    public function testResolveNamespace()
    {
        $document = new Document();
        $document->formatOutput = true;
        $root = $document->createElementNS('http://www.w3.org/2005/Atom', 'element');
        $document->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sped', 'http://nfephp.org/ns/1.0');
        $root->setAttribute('targetNamespace', 'http://nfephp.org/ns/1.0');
        $item = $document->createElementNS('http://nfephp.org/ns/1.0', 'sped:firstName', 'Tester');
        $root->appendChild($item);

        $this->assertEquals('http://nfephp.org/ns/1.0', $document->resolveNamespace('sped', true));
    }

    public function testGetNamespaceCode()
    {
        $document = new Document();
        $document->formatOutput = true;
        $root = $document->createElementNS('http://www.w3.org/2005/Atom', 'element');
        $document->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sped', 'http://nfephp.org/ns/1.0');
        $root->setAttribute('targetNamespace', 'http://nfephp.org/ns/1.0');
        $item = $document->createElementNS('http://nfephp.org/ns/1.0', 'sped:firstName', 'Tester');
        $root->appendChild($item);

        $this->assertEquals('xmlns:sped', $document->getNamespaceCode('http://nfephp.org/ns/1.0'));
        $this->assertEquals('sped', $document->getNamespaceCode('http://nfephp.org/ns/1.0', true));
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
