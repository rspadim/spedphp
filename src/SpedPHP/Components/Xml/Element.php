<?php

namespace SpedPHP\Components\Xml;

/**
 * @category   SpedPHP
 * @package    SpedPHP\Components\Xml
 * @copyright  Copyright (c) 2012
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @author     Antonio Spinelli <tonicospinelli85@gmail.com>
 */
class Element extends \DOMElement
{

    /**
     *
     * @return \SpedPHP\Components\Xml\NodeIterator
     */
    public function getIterator()
    {
        return new NodeIterator($this);
    }

    /**
     * Adds new child at the end of the children.
     *
     * @param \DOMNode $newNode <p>The appended child.</p>
     * @param boolean  $unique  [optional]<p>
     *                          If sets TRUE, search if exists the same node.
     * </p>
     *
     * @return DOMNode The node added or if is unique, returns the node found.
     */
    public function appendChild(\DOMNode $newNode, $unique = false)
    {
        $node = null;
        if ($unique) {
            $node = parent::getElementsByTagName($newNode->localName)->item(0);
        }

        if ($node !== null) {
            $newNode = parent::replaceChild($newNode, $node);
        } else {
            $newNode = parent::appendChild($newNode);
        }

        return $newNode;
    }

    /**
     * Adds a new child before a reference node
     *
     * @link http://php.net/manual/en/domnode.insertbefore.php
     *
     * @param DOMNode $newnode <p>
     *                         The new node.
     * </p>
     * @param DOMNode $refnode [optional] <p>
     *                         The reference node. If not supplied, <i>newnode</i> is
     *                         appended to the children.
     * </p>
     *
     * @return DOMNode The inserted node.
     */
    public function insertBefore(\DOMNode $newnode, \DOMNode $refnode = null)
    {
        $newNode = parent::insertBefore($newnode, $refnode);

        return $newNode;
    }

    /**
     * (PHP 5)<br/>
     * Replaces a child
     *
     * @link http://php.net/manual/en/domnode.replacechild.php
     *
     * @param DOMNode $newnode <p>
     *                         The new node. It must be a member of the target document, i.e.
     *                         created by one of the DOMDocument->createXXX() methods or imported in
     *                         the document by .
     * </p>
     * @param DOMNode $oldnode <p>
     *                         The old node.
     * </p>
     *
     * @return DOMNode The old node or false if an error occur.
     */
    public function replaceChild(\DOMNode $newnode, \DOMNode $oldnode)
    {
        $newNode = parent::replaceChild($newnode, $oldnode);

        return $newNode;
    }

    /**
     * (PHP 5)<br/>
     * Removes children by tag name from list of children
     *
     * @link http://php.net/manual/en/domnode.removechild.php
     *
     * @param string $tagName <p>
     *                        The tagName to remove children.
     * </p>
     *
     * @return boolean If the child could be removed the function returns true.
     */
    public function removeElementsByTagName($name)
    {
        $nodes = $this->getElementsByTagName($name);
        foreach ($nodes as $node)
            $this->removeChild($node);

        return true;
    }

    /**
     * (PHP 5 &gt;= 5.2.0)<br/>
     * Get an XPath for a node
     *
     * @link http://php.net/manual/en/domnode.getnodepath.php
     * @return string a string containing the XPath, or <b>NULL</b> in case of an error.
     */
    public function getNodeXPath()
    {
        $result = '';
        $node = $this;
        /* @var $parentNode \DOMElement */
        while ($parentNode = $node->parentNode) {
            $nodeIndex = -1;
            $nodeTagIndex = 0;
            $hasSimilarNodes = false;
            do {
                $nodeIndex++;
                $testNode = $parentNode->childNodes->item($nodeIndex);

                if ($testNode->nodeName == $node->nodeName
                    AND $testNode->parentNode->isSameNode($node->parentNode)
                        AND $testNode->childNodes->length > 0
                ) {
                    $nodeTagIndex++;
                }
            } while (!$node->isSameNode($testNode));

            if ($hasSimilarNodes) {
                $result = "/{$node->nodeName}[{$nodeTagIndex}]" . $result;
            } else {
                $result = "/{$node->nodeName}" . $result;
            }
            $node = $parentNode;
        };

        return $result;
    }
}