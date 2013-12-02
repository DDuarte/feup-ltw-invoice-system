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
