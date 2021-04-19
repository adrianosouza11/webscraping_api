<?php


namespace App\Components\WebScrapingExtraction\Traits;


trait WebScrapingTrait
{
    public function getDomainUrl() : string
    {
        return  $this->domain_url;
    }

    public function getDomainSelectorSet() : array
    {
        return $this->domainSelectorSet;
    }
}