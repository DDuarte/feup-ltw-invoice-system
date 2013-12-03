<?php
    function xml_encode($mixed, $attrs = [], $domElement = null, $DOMDocument = null) {
        if (is_null($DOMDocument)) {
            $DOMDocument = new DOMDocument;
            $DOMDocument->formatOutput = true;
            xml_encode($mixed, null, $DOMDocument, $DOMDocument);

            foreach ($attrs as $name => $value) {
                $domAttribute = $DOMDocument->createAttribute($name);
                $domAttribute->value = $value;
                $DOMDocument->firstChild->appendChild($domAttribute);
            }

            return $DOMDocument->saveXML();
        }
        else {
            if (is_array($mixed)) {
                foreach ($mixed as $index => $mixedElement) {
                    if (is_int($index)) {
                        if ($index === 0) {
                            $node = $domElement;
                        }
                        else {
                            /** @var $DOMDocument DOMDocument */
                            /** @var $domElement DOMElement */
                            $node = $DOMDocument->createElement($domElement->tagName);
                            $domElement->parentNode->appendChild($node);
                        }
                    }
                    else {
                        /** @var $DOMDocument DOMDocument */
                        $plural = $DOMDocument->createElement($index);
                        /** @var $domElement DOMElement */
                        $domElement->appendChild($plural);
                        $node = $plural;
                    }
    
                    xml_encode($mixedElement, null, $node, $DOMDocument);
                }
            }
            else {
                $mixed = is_bool($mixed) ? ($mixed ? 'true' : 'false') : $mixed;
                /** @var $DOMDocument DOMDocument */
                /** @var $domElement DOMElement */
                $domElement->appendChild($DOMDocument->createTextNode($mixed));
            }
        }

        return null;
    }

    function xml_decode($xml_string)
    {
        $root = new DOMDocument;
        $root->loadXML($xml_string);

        return xml_to_array($root);
    }

    function xml_to_array($root)
    {
        $result = array();

        if ($root->hasChildNodes()) {
            $children = $root->childNodes;
            if ($children->length == 1) {
                $child = $children->item(0);
                if ($child->nodeType == XML_TEXT_NODE) {
                    $result['_value'] = $child->nodeValue;
                    return count($result) == 1
                        ? $result['_value']
                        : $result;
                }
            }
            $groups = array();
            foreach ($children as $child) {
                if ($child->nodeName == "#text")
                    continue;
                if (!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = xml_to_array($child);
                } else {
                    if (!isset($groups[$child->nodeName])) {
                        $result[$child->nodeName] = array($result[$child->nodeName]);
                        $groups[$child->nodeName] = 1;
                    }
                    $result[$child->nodeName][] = xml_to_array($child);
                }
            }
        }

        return $result;
    }
