<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;
use InvalidArgumentException;

class ExtensionListSvgSanitizer
{
    /**
     * Remove scripts, iframes e atributos de evento; valida raiz &lt;svg&gt;.
     *
     * @throws InvalidArgumentException
     */
    public static function sanitize(string $xml): string
    {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument;

        if (! $doc->loadXML($xml, LIBXML_NONET)) {
            throw new InvalidArgumentException('O arquivo não é um XML/SVG válido.');
        }

        $root = $doc->documentElement;
        if (! $root instanceof DOMElement || strtolower($root->localName) !== 'svg') {
            throw new InvalidArgumentException('O arquivo deve ser um SVG (elemento raiz &lt;svg&gt;).');
        }

        $removeTags = ['script', 'iframe'];
        $xpath = new DOMXPath($doc);
        foreach ($removeTags as $tag) {
            $nodes = $xpath->query('//*[local-name()="'.$tag.'"]');
            if ($nodes) {
                foreach ($nodes as $node) {
                    $node->parentNode?->removeChild($node);
                }
            }
        }

        $onAttrs = $xpath->query('//@*[starts-with(local-name(), "on")]');
        if ($onAttrs) {
            foreach ($onAttrs as $attr) {
                $owner = $attr->ownerElement;
                if ($owner instanceof DOMElement) {
                    $owner->removeAttribute($attr->nodeName);
                }
            }
        }

        $hrefAttrs = $xpath->query('//@*[local-name()="href" or local-name()="xlink:href"]');
        if ($hrefAttrs) {
            foreach ($hrefAttrs as $attr) {
                $val = strtolower(trim((string) $attr->nodeValue));
                if (str_starts_with($val, 'javascript:')) {
                    $owner = $attr->ownerElement;
                    if ($owner instanceof DOMElement) {
                        $owner->removeAttribute($attr->nodeName);
                    }
                }
            }
        }

        $out = $doc->saveXML($root);
        if ($out === false || $out === '') {
            throw new InvalidArgumentException('Não foi possível gravar o SVG sanitizado.');
        }

        return $out;
    }
}
