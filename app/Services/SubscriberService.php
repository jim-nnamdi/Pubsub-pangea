<?php 

namespace App\Services; 
use PhpAmqpLib\Connection\AMQPStreamConnection;

class SubscriberService {

  public function subscribeToPublishedTopics($topic) {

    $connection = new AMQPStreamConnection(
      config('RabbitMQ.server'), 
      config('RabbitMQ.server_port'), 
      config('RabbitMQ.server_name'), 
      config('RabbitMQ.server_pwd')
    );

    $channel = $connection->channel();

    $channel->queue_declare($topic, false, false, false, false); 

    return '[x] Please wait for incoming messages, press CTRL + C to exit';

    $callback = function($msg){
      return '[x]'. $msg->body . 'received';
    };

    $channel->basic_consume($topic, '', false,true, false, false, $callback );

    while(count($channel->callbacks)){
      $channel->wait();
    }
  }
}