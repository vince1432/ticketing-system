<?php

namespace App\Http\Controllers;

use App\Contract\TicketPriorityServiceInterface;
use Illuminate\Http\Request;

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
        // $this->model_string = "Ticket Priority";
        // // set base controller service
        // $this->setService($this->ticket_priority_service);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if(request()->user()->cannot('viewAny', Module::class)) {
        //     return response()->json([
        //         "status" => "Unauthorized",
        //         "message" => "You can't access this information."
        //     ], 401);
        // }

        $query_params = request()->query();
        $item_count = $query_params['item_count'] ?? 10;

        $response = $this->ticket_priority_service->index($item_count);
        $response["status"] = "Success";
        $response["message"] = "Success.";

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

        // if(request()->user()->cannot('viewAny', Module::class)) {
        //     return response()->json([
        //         "status" => "Unauthorized",
        //         "message" => "You can't access this information."
        //     ], 401);
        // }

        $new_record = $this->ticket_priority_service->store($validated);
        $response = array(
            "status" => "Success",
            "message" => "Ticket Priority successfuly created.",
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
        $data = $this->ticket_priority_service->show($id);

        // missing model
        if($this->ticket_priority_service->status === 404)
            $response = [
                "status" => "Not found",
                "message" => "Ticket Priority not found."
            ];
        // // unauthorize access
        // else if(request()->user()->cannot('view', new Module($data))) {
        //     $response =[
        //         "status" => "Unauthorized",
        //         "message" => "You can't access this information."
        //     ];
        //     $this->ticket_priority_service->status = 401;
        // }
        else
            $response = [
                "status" => "Success",
                "message" => "Success.",
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

        $data = $this->ticket_priority_service->update($validated, $id);

        if ($this->ticket_priority_service->status === 404)
            $response = array(
                "status" => "Not found",
                "message" => "Ticket Priority not found."
            );
        else
            $response = array(
                "status" => "Success",
                "message" => "Ticket Priority successfuly updated.",
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
       $this->ticket_priority_service->destroy($id);

        $response = array(
            "status" => "Success",
            "message" => "Ticket Priority successfuly removed."
        );
        return response()->json($response, $this->ticket_priority_service->status);
    }
}
