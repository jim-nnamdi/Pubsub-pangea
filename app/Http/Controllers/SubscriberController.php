<?php

namespace App\Http\Controllers;

use App\Services\SubscriberService;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public $subscriberService; 

    public function __construct(SubscriberService $subscriberService){
        $this->subscriberService = $subscriberService;
    }

    public function subscribeToPublishedTopics(Request $request, $topic){
        return $this->subscriberService->subscribeToPublishedTopics($request, $topic);
    }
}
