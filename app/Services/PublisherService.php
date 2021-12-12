<?php 

namespace App\Services;

use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PublisherService {
  
  public function createTopicForPublishing(Request $request, $topic){

    $connection = new AMQPStreamConnection(
      config('RabbitMQ.server'), 
      config('RabbitMQ.server_port'), 
      config('RabbitMQ.server_name'), 
      config('RabbitMQ.server_pwd')
    );

    $channel = $connection->channel(); 
    
    $channel->queue_declare($topic, false, false, false, false);

    if (empty($request->data)) {
      $request->data = "info: Hello World!";
  }
    
    $subscriptionMsg = new AMQPMessage($request->data);
    
    $channel->basic_publish($subscriptionMsg,'', $topic);
    
    return '[x] sent subscription message to subscribers';
    
    $channel->close(); 
    
    $connection->close();

  }
}