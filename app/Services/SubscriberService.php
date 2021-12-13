<?php 

namespace App\Services; 
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Http\Resources\SubscriberServiceResource;

class SubscriberService {

  public function subscribeToPublishedTopics(Request $request, $topic) {

    $connection = new AMQPStreamConnection(
      config('RabbitMQ.server'), 
      config('RabbitMQ.server_port'), 
      config('RabbitMQ.server_name'), 
      config('RabbitMQ.server_pwd')
    );

    $channel = $connection->channel();

    $channel->queue_declare($topic, false, false, false, false); 

    $callback = function($msg){
      return '[x]'. $msg->body . 'received';
    };

    $channel->basic_consume($topic, '', false,true, false, false, $callback );

    while(count($channel->callbacks)){
      $channel->wait();
    }

  $returnSpecificResourceForSubscriber = Subscriber::where("topic", $topic)->first();
    
    return new SubscriberServiceResource($returnSpecificResourceForSubscriber);
  }
}