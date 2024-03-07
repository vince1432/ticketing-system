<?php

namespace App\Http\Controllers;

use App\Contract\TicketServiceInterface;
use Illuminate\Http\Request;

class TicketController extends BaseController
{
    private $ticket_service;

    /**
     * set service to be used.
     *
     * @param  App\Contract\TicketServiceInterface  $ticket_service
     * @return void
     */
    public function __construct(TicketServiceInterface $ticket_service)
    {
        $this->ticket_service = $ticket_service;
        $this->model_string = "Ticket";
        // set base controller service
        $this->setService($this->ticket_service);
    }

    public function close(Request $request, $id)
    {
        $comment = $this->ticket_service->close($request, $id);
        if ($this->ticket_service->status === 404)
            $response = array(
                "status" => "Not found",
                "message" => "Comment not found."
            );
        else
            $response = array(
                "status" => "Success",
                "message" => "Comment successfuly updated.",
                "data" => $comment
            );

        return response()->json($response, $this->ticket_service->status);
    }

    public function comments(Request $reqquest)
    {
        $response = $this->ticket_service->comments($reqquest);
        $response["status"] = "Success";
        $response["message"] = "Success.";

        return response()->json($response, $this->ticket_service->status);
    }

    public function comment($id)
    {
        $comment = $this->ticket_service->comment($id);
        if ($this->ticket_service->status === 404)
            $response = array(
                "status" => "Not found",
                "message" => "Comment not found."
            );
        else
            $response = array(
                "status" => "Success",
                "message" => "Success.",
                "data" => $comment
            );

        return response()->json($response, $this->ticket_service->status);
    }

    public function addComment(Request $request)
    {
        $comment = $this->ticket_service->addComment($request);
        $response = array(
            "status" => "Success",
            "message" => "Comment successfuly created.",
            "data" => $comment
        );

        return response()->json($response, $this->ticket_service->status);
    }

    public function updateComment(Request $request, $id)
    {
        $comment = $this->ticket_service->updateComment($request, $id);
        if ($this->ticket_service->status === 404)
            $response = array(
                "status" => "Not found",
                "message" => "Comment not found."
            );
        else
            $response = array(
                "status" => "Success",
                "message" => "Comment successfuly updated.",
                "data" => $comment
            );

        return response()->json($response, $this->ticket_service->status);
    }

    public function removeComment($id)
    {
        $this->ticket_service->removeComment($id);
        $response = array(
            "status" => "Success",
            "message" => "Comment successfuly removed."
        );

        return response()->json($response, $this->ticket_service->status);
    }
}
