<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SelectorCollectionDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('selector_collection_default')->insert([
            [
                'domain_url' => 'www.amazon.com.br',
                'name' => 'Amazon Brazil',
                'collection_selector_json' => '{"title":"#productTitle","description":"#productDescription","price":"#price_inside_buybox","image":"#imgTagWrapperId > img"}',
                'created_at' => time(),
                'updated_at' => time(),
            ],
            [
                'domain_url' => 'www.magazineluiza.com.br',
                'name' => 'Magazine Luiza',
                'collection_selector_json' => '{"title":".header-product__title","description":".description__container-text","price":".price-template__text","image":".showcase-product__big-img.js-showcase-big-img"}',
                'created_at' => time(),
                'updated_at' => time(),
            ],
            [
                'domain_url' => 'www.zattini.com.br',
                'name' => 'Zattini',
                'collection_selector_json' => '{"title":".short-description > h1","description":".description","price":".default-price","image":".floating-button__wrap > .floating-button__wrap--col > img"}',
                'created_at' => time(),
                'updated_at' => time()
            ]
        ]);
    }
}
