<?php

namespace App\Components\WebScrapingExtraction;

use App\Components\WebScrapingExtraction\Exceptions\WebScrapingException;
use App\Components\WebScrapingExtraction\Traits\WebScrapingTrait;
use App\Components\WebScrapingExtraction\Utilities\Utilities;
use App\Models\Products;
use App\Models\SelectorCollectionDefault;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;

class WebScraping
{
    use WebScrapingTrait;

    private const PATTERN_DOMAIN = '/^.+?[^\/:](?=[?\/]|$)/m';

    private const PATTERN_PROTOCOLS_WEB = '/https?:\/\//i';

    /** @var string $product_url */
    private $product_url;

    /** @var string $domain_url */
    private $domain_url;

    private $domainSelectorSet = array();

    private $product = null;

    private $crawlerInstance;

    public function __construct(string $productUrl)
    {
        $this->product_url = $productUrl;
        $this->domain_url = $this->getUrlDomain($productUrl);
        $this->crawlerInstance = new Crawler($this->requestProductPage());
    }

    public function run() : array
    {
        $this->checksOrDiscontinuesProduct();

        if (empty($this->product))
            return $this->doScraping();

        return $this->product;
    }

    public function doScraping() : array
    {
        $this->processDomainSelectors();

        $this->cutDataProductSite();

        $this->processNewProduct();

        $this->persistNewProduct();

        return $this->product;
    }

    private function getUrlDomain(string $fullUrl) : string
    {
        preg_match_all(self::PATTERN_DOMAIN, $fullUrl, $matches, PREG_SET_ORDER, 0);

        if (!isset($matches[0], $matches[0][0]))
            throw new WebScrapingException("Can't get the domain");

        return preg_replace(self::PATTERN_PROTOCOLS_WEB, "", $matches[0][0]);
    }

    public function requestProductPage() : string
    {
        header('Content-type: text/plain');
        return file_get_contents($this->product_url);
    }

    private function processDomainSelectors() : void
    {
        $mSelectorCollectionDefault = SelectorCollectionDefault::where(['domain_url' => $this->domain_url])->first();

        $this->domainSelectorSet = (array) json_decode($mSelectorCollectionDefault->collection_selector_json);
    }

    private function cutDataProductSite() : void
    {
        foreach ($this->domainSelectorSet as $key => $value){
            if ($key != 'image')
                $this->product[$key] = $this->crawlerInstance->filter($value)->text();
            else
                $this->product[$key] = $this->crawlerInstance->filter($value)->attr('src');
        }
    }

    private function processNewProduct() : void
    {
        foreach ($this->product as $key => $value){
            switch ($key) {
                case 'image' :
                    $value = Utilities::replaceLinkBase64(trim($value));
                    $this->product[$key] = !Utilities::is_base64($value) ? $this->imageUrlToBase64($value) : $value;
                break;
                case 'price':
                    $this->product[$key] = Utilities::usCoin($value);
                break;
            }
        }
    }

    public function persistNewProduct() : void
    {
        $mProduct = new Products();

        $mProduct->create([
            'title' => $this->product['title'],
            'description' => $this->product['description'],
            'price' => $this->product['price'],
            'image_base64' => $this->product['image'],
            'url_origin' => $this->product_url,
            'datetime_created' => date('Y-m-d H:i:s'),
        ]);

        $this->product = $mProduct->getAttributes();

        $this->fillAttributesLabel();
    }

    private function checksOrDiscontinuesProduct() : void
    {
        $urlSearch = preg_replace(self::PATTERN_PROTOCOLS_WEB,'',$this->product_url);

        $mProduct = Products::where('url_origin','like',"%$urlSearch%")
            ->where(['is_discontinued' => 0])
            ->first(DB::raw("*, TIMEDIFF(CURRENT_TIME(), DATE_FORMAT(datetime_created, '%T')) as time_created"));

        if($mProduct != null && $mProduct->time_created >= 1){
            $mProduct->is_discontinued = 1;
            $mProduct->saveOrFail();

            return ;
        }

        if ($mProduct == null)
            return ;

        unset($mProduct->time_created);

        $this->product = $mProduct->getAttributes();

        $this->fillAttributesLabel();
    }

    private static function imageUrlToBase64(string $imageUrl) : string
    {
        $imageUrl = !preg_match('/https?:\/\//i',$imageUrl) ?
            preg_replace('/https?:\/\/|\/\//i',"http://",$imageUrl)
            : $imageUrl;

        return base64_encode(file_get_contents($imageUrl));
    }

    private function fillAttributesLabel() : void
    {
        $getAttributes = $this->product;
        $getColumnRest = $this->arrayColumnsRest();

        $attrTemp = array();
        foreach ($getColumnRest as $k => $v){
            $attrTemp[$v] = $getAttributes[$k];
        }

        $this->product = $attrTemp;
    }

    private function arrayColumnsRest(): array
    {
        return [
            'title' => 'title',
            'description' => 'description',
            'price' => 'price',
            'url_origin' => 'url',
            'image_base64' => 'image',
        ];
    }
}