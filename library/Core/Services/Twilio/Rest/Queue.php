<?php

class Core_Services_Twilio_Rest_Queue
    extends Core_Services_Twilio_InstanceResource {

    protected function init($client, $uri) {
        $this->setupSubresources('members');
    }
}

