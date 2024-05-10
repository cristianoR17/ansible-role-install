<?php
 
   if (!($socket = socketConnect())) {
           return;
   }
  
 $data = getAgents($socket);
 socket_close($socket);

 function getAgents($socket)
 {

     global $fields;

     $data = [];
     while (true) {
         $buf = "";

         // Validation
         if (!socket_recv($socket, $buf, 1024, 0)) {
             $error = socket_strerror(socket_last_error($socket));
             break;
         }

         // End
         if (strstr($buf, "END#")) {
             break;
         }

         $data[] = substr($buf, 0, strpos($buf, "#"));
     };
 print_r($data);exit;
 }

 function socketConnect()
 {
     global $_CONFIG;

     $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

     socket_sendto($socket, "AGENTS", strlen("AGENTS"), 0, '10.11.195.183', 9000);

     socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 5, "usec" => 0));
     return $socket;
 }
 
 ?>
