<?php

namespace App\Http\Controllers\Extraction;

use App\Components\WebScrapingExtraction\WebScraping;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebScrapingRequest;
use Illuminate\Http\JsonResponse;

class WebScrapingController extends Controller
{
    public function doScraping(WebScrapingRequest $request) : JsonResponse
    {
        $getUrlOrigin = $request->query('urlOrigin');

        $doScraping = (new WebScraping($getUrlOrigin))->run();

        return response()->json($doScraping);
    }
}