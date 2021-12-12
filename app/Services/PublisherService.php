<?php 

namespace App\Services; 
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PublisherService {
  
  public function createTopicForPublishing($topic, $data){

    $connection = new AMQPStreamConnection(
      config('RabbitMQ.server'), 
      config('RabbitMQ.server_port'), 
      config('RabbitMQ.server_name'), 
      config('RabbitMQ.server_pwd')
    );

    $channel = $connection->channel(); 
    
    $channel->queue_declare($topic, false, false, false, false);
    
    $subscriptionMsg = new AMQPMessage($data);
    
    $channel->basic_publish($subscriptionMsg,'', $topic);
    
    echo '[x] sent subscription message to subscribers';
    
    $channel->close(); 
    
    $connection->close();

  }
}