<?php

namespace App\Http\Controllers;

use App\Constants\Message;
use App\Constants\RespStat;
use App\Contract\TicketPriorityServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketPriorityController extends Controller
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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query_params = request()->query();
        $item_count = $query_params['item_count'] ?? 10;

        $response = $this->ticket_priority_service->index($item_count);
        $response["status"] = RespStat::SUCCESS;
        $response["message"] = Message::SUCCESS;

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|integer|unique:ticket_prioties|level',
            'name' => 'required|min:1|max:15|unique:ticket_prioties|name',
        ]);

        if(!Gate::allows('is-admin')) {
            return response()->json([
                "status" => RespStat::UNAUTHORIZED,
                "message" => Message::UNAUTHORIZED
            ], 401);
        }

        $new_record = $this->ticket_priority_service->store($validated);
        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "Ticket Priority " . Message::CREATED_SUFF,
            "data" => $new_record
        );

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!Gate::allows('is-admin')) {
            return response()->json([
                "status" => RespStat::UNAUTHORIZED,
                "message" => Message::UNAUTHORIZED
            ], 401);
        }

        $data = $this->ticket_priority_service->show($id);

        // missing model
        if($this->ticket_priority_service->status === 404)
            $response = [
                "status" => RespStat::NOT_FOUND,
                "message" => "Ticket Priority " . Message::NOT_FOUND_SUFF
            ];
        else
            $response = [
                "status" => RespStat::SUCCESS,
                "message" => Message::SUCCESS,
                "data" => $data
            ];

        return response()->json($response, $this->ticket_priority_service->status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'level' => 'required|integer|unique:ticket_prioties|level,' .$id,
            'name' => 'required|min:1|max:15|unique:ticket_prioties|name,' .$id,
        ]);

        if(!Gate::allows('is-admin')) {
            return response()->json([
                "status" => RespStat::UNAUTHORIZED,
                "message" => Message::UNAUTHORIZED
            ], 401);
        }

        $data = $this->ticket_priority_service->update($validated, $id);

        if ($this->ticket_priority_service->status === 404)
            $response = array(
                "status" => RespStat::NOT_FOUND,
                "message" => "Ticket Priority " . Message::NOT_FOUND_SUFF
            );
        else
            $response = array(
                "status" => RespStat::SUCCESS,
                "message" => "Ticket Priority " . Message::UPDATED_SUFF,
                "data" => $data
            );

        return response()->json($response, $this->ticket_priority_service->status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!Gate::allows('is-admin')) {
            return response()->json([
                "status" => RespStat::UNAUTHORIZED,
                "message" => Message::UNAUTHORIZED
            ], 401);
        }

       $this->ticket_priority_service->destroy($id);

        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "Ticket Priority " . Message::REMOVED_SUFF
        );
        return response()->json($response, $this->ticket_priority_service->status);
    }
}
