<?php

namespace App\Http\Controllers;

use App\Constants\Message;
use App\Constants\RespStat;
use App\Contract\ModuleServiceInterface;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    private $module_service;

    /**
     * set service to be used.
     *
     * @param  App\Contract\ModuleServiceInterface  $module_service
     * @return void
     */
    public function __construct(ModuleServiceInterface $module_service)
    {
        $this->module_service = $module_service;
        // $this->model_string = "Module";
        // // set base controller service
        // $this->setService($this->module_service);
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

        $response = $this->module_service->index($item_count);
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
            'name' => 'required|min:1|max:15|unique:ticket_statuses,name',
            'description' => 'required|min:1|max:255'
        ]);

        // if(request()->user()->cannot('viewAny', Module::class)) {
        //     return response()->json([
        //         "status" => "Unauthorized",
        //         "message" => "You can't access this information."
        //     ], 401);
        // }

        $new_record = $this->module_service->store($validated);
        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "Module " . Message::CREATED_SUFF,
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
        $data = $this->module_service->show($id);

        // missing model
        if($this->module_service->status === 404)
            $response = [
                "status" => RespStat::NOT_FOUND,
                "message" => "Module " . Message::NOT_FOUND_SUFF
            ];
        // // unauthorize access
        // else if(request()->user()->cannot('view', new Module($data))) {
        //     $response =[
        //         "status" => "Unauthorized",
        //         "message" => "You can't access this information."
        //     ];
        //     $this->module_service->status = 401;
        // }
        else
            $response = [
                "status" => RespStat::SUCCESS,
                "message" => Message::SUCCESS,
                "data" => $data
            ];

        return response()->json($response, $this->module_service->status);
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
            'name' => 'required|min:1|max:15|unique:ticket_statuses,name,' . $id,
            'description' => 'required|min:1|max:255',
        ]);

        $data = $this->module_service->update($validated, $id);

        if ($this->module_service->status === 404)
            $response = array(
                "status" => RespStat::NOT_FOUND,
                "message" => "Module " . Message::NOT_FOUND_SUFF
            );
        else
            $response = array(
                "status" => RespStat::SUCCESS,
                "message" => "Module " . Message::UPDATED_SUFF,
                "data" => $data
            );

        return response()->json($response, $this->module_service->status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $this->module_service->destroy($id);

        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "Module "  . Message::REMOVED_SUFF
        );
        return response()->json($response, $this->module_service->status);
    }
}
