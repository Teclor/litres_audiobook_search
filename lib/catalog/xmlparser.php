<?php

namespace Catalog;


use Exception\Xml\ContentNotLoadedException;

class XmlParser
{
    private string $filePath;
    private \SimpleXMLElement $xmlElement;
    private array $offers;

    /**
     * @throws ContentNotLoadedException
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->load();
    }

    /**
     * @throws ContentNotLoadedException
     */
    public function load()
    {
        $xmlElement = simplexml_load_file($this->filePath);
        if ($xmlElement === false) {
            throw new ContentNotLoadedException($this->filePath);
        }
        $this->xmlElement = $xmlElement;
    }
    
    public function getOffers(): array
    {
        if (empty($this->offers)) {
            $this->parse();
        }
        return $this->offers;
    }
    
    public function parse(): array
    {
        $this->offers = [];
        if (empty($this->xmlElement)) {
            return [];
        }

        foreach ($this->xmlElement->shop->offers->offer as $xmlOffer) {
            $offer = [];
            foreach ($xmlOffer->attributes() as $attribute) {
                if ($attribute->getName() === 'id') {
                    $offer[$attribute->getName()] = (int)$attribute;
                }
                elseif($attribute->getName() === 'available') {
                    $offer[$attribute->getName()] = (bool)$attribute;
                }
            }
            foreach ($xmlOffer as $value) {
                if ($value->getName() === 'param') {
                    $offer[(string)$value->attributes()] = (string)$value;
                }
                else {
                    $offer[$value->getName()] = (string)$value;
                }
            }
            $this->offers[] = $offer;
        }

        return $this->offers;
    }
}