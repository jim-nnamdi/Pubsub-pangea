<?php

namespace App\Http\Controllers;

use App\Services\PublisherService;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public $publisherService; 

    public function __construct(PublisherService $publisherService)
    {
        $this->publisherService = $publisherService;
    }

    public function createTopicForPublishing(Request $request, $topic){
        return $this->publisherService->createTopicForPublishing($request, $topic);
    }
}
