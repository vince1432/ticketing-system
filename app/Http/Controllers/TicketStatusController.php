<?php

namespace App\Http\Controllers;

use App\Contract\TicketStatusServiceInterface;

class TicketStatusController extends BaseController
{
    private $ticket_status_service;

    /**
     * set service to be used.
     *
     * @param  App\Contract\Ticket  $ticket_service
     * @return void
     */
    public function __construct(TicketStatusServiceInterface $ticket_status_service)
    {
        $this->ticket_status_service = $ticket_status_service;
        $this->model = "Ticket Status";
        // set base controller service
        $this->setService($this->ticket_status_service);
    }
}
