<?php

namespace App\Http\Controllers;

use App\Contract\TicketPriorityServiceInterface;

class TicketPriorityController extends BaseController
{
    private $ticket_priority_service;

    /**
     * set service to be used.
     *
     * @param  App\Contract\TicketServiceInterface  $ticket_service
     * @return void
     */
    public function __construct(TicketPriorityServiceInterface $ticket_priority_service)
    {
        $this->ticket_priority_service = $ticket_priority_service;
        $this->model = "Ticket Priority";
        // set base controller service
        $this->setService($this->ticket_priority_service);
    }
}
