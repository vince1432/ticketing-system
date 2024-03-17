<?php

namespace App\Http\Controllers;

use App\Constants\Message;
use App\Constants\RespStat;
use App\Contract\TicketServiceInterface;
use Illuminate\Http\Request;

class TicketController extends Controller
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
        // $this->model_string = "Ticket";
        // // set base controller service
        // $this->setService($this->ticket_service);
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

        $response = $this->ticket_service->index($item_count);
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
            'title' => 'required|max:255',
            'summary' => 'required|min:1|max:1000',
            'status_id' => 'required|exists:ticket_statuses,id',
            'priority_id' => 'required|exists:ticket_prioties,id',
            'module_id' => 'nullable|exists:modules,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // if(request()->user()->cannot('viewAny', Module::class)) {
        //     return response()->json([
        //         "status" => "Unauthorized",
        //         "message" => "You can't access this information."
        //     ], 401);
        // }

        $new_record = $this->ticket_service->store($validated);
        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "Ticket " . Message::CREATED_SUFF,
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
        $data = $this->ticket_service->show($id);

        // missing model
        if($this->ticket_service->status === 404)
            $response = [
                "status" => RespStat::NOT_FOUND,
                "message" => "Ticket " . Message::NOT_FOUND_SUFF
            ];
        // // unauthorize access
        // else if(request()->user()->cannot('view', new Module($data))) {
        //     $response =[
        //         "status" => "Unauthorized",
        //         "message" => "You can't access this information."
        //     ];
        //     $this->ticket_service->status = 401;
        // }
        else
            $response = [
                "status" => RespStat::SUCCESS,
                "message" => Message::SUCCESS,
                "data" => $data
            ];

        return response()->json($response, $this->ticket_service->status);
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
            'title' => 'nullable|max:255',
            'summary' => 'nullable|min:1|max:1000',
            'status_id' => 'required|exists:ticket_statuses,id',
            'priority_id' => 'nullable|exists:ticket_prioties,id',
            'module_id' => 'nullable|exists:modules,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);


        $data = $this->ticket_service->update($validated, $id);

        if ($this->ticket_service->status === 404)
            $response = array(
                "status" => RespStat::NOT_FOUND,
                "message" => "Ticket " . Message::NOT_FOUND_SUFF
            );
        else
            $response = array(
                "status" => RespStat::SUCCESS,
                "message" => "Ticket " . Message::UPDATED_SUFF,
                "data" => $data
            );

        return response()->json($response, $this->ticket_service->status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $this->ticket_service->destroy($id);

        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "Ticket " . Message::REMOVED_SUFF
        );
        return response()->json($response, $this->ticket_service->status);
    }

    public function close(Request $request, $id)
    {
        // $validated = $request->validate([
        //     'resolution' => 'required|min:1|max:1000',
        // ]);
        $comment = $this->ticket_service->close($request, $id);
        if ($this->ticket_service->status === 404)
            $response = array(
                "status" => RespStat::NOT_FOUND,
                "message" => "Comment " . Message::NOT_FOUND_SUFF
            );
        else
            $response = array(
                "status" => RespStat::SUCCESS,
                "message" => "Comment " . Message::UPDATED_SUFF,
                "data" => $comment
            );

        return response()->json($response, $this->ticket_service->status);
    }

    public function comments(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id'
        ]);

        $response = $this->ticket_service->comments($validated);
        $response["status"] = RespStat::SUCCESS;
        $response["message"] = Message::SUCCESS;

        return response()->json($response, $this->ticket_service->status);
    }

    public function comment($id)
    {
        $comment = $this->ticket_service->comment($id);
        if ($this->ticket_service->status === 404)
            $response = array(
                "status" => RespStat::NOT_FOUND,
                "message" => "Comment " . Message::NOT_FOUND_SUFF
            );
        else
            $response = array(
                "status" => RespStat::SUCCESS,
                "message" => Message::SUCCESS,
                "data" => $comment
            );

        return response()->json($response, $this->ticket_service->status);
    }

    public function addComment(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'comment' => 'required|min:1|max:1000'
        ]);

        $comment = $this->ticket_service->addComment($validated);
        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "Comment " . Message::CREATED_SUFF,
            "data" => $comment
        );

        return response()->json($response, $this->ticket_service->status);
    }

    public function updateComment(Request $request, $id)
    {
        $validated = $request->validate([
            'comment' => 'required|min:1|max:1000'
        ]);

        $comment = $this->ticket_service->updateComment($validated, $id);
        if ($this->ticket_service->status === 404)
            $response = array(
                "status" => RespStat::NOT_FOUND,
                "message" => "Comment " . Message::NOT_FOUND_SUFF
            );
        else
            $response = array(
                "status" => RespStat::SUCCESS,
                "message" => "Comment " . Message::UPDATED_SUFF,
                "data" => $comment
            );

        return response()->json($response, $this->ticket_service->status);
    }

    public function removeComment($id)
    {
        $this->ticket_service->removeComment($id);
        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "Comment " . Message::REMOVED_SUFF
        );

        return response()->json($response, $this->ticket_service->status);
    }
}
