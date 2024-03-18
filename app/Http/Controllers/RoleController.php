<?php

namespace App\Http\Controllers;

use App\Constants\Message;
use App\Constants\RespStat;
use App\Contract\RoleServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    private $role_service;

    /**
     * set service to be used.
     *
     * @param  App\Contract\RoleServiceInterface  $role_service
     * @return void
     */
    public function __construct(RoleServiceInterface $role_service)
    {
        $this->role_service = $role_service;
        // $this->model_string = "Role";
        // // set base controller service
        // $this->setService($this->role_service);
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

        $response = $this->role_service->index($item_count);
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
            'name' => 'required|min:1|max:15|unique:roles,name',
            'level' => 'required|integer'
        ]);

        if(!Gate::allows('is-admin')) {
            return response()->json([
                "status" => RespStat::UNAUTHORIZED,
                "message" => Message::UNAUTHORIZED
            ], 401);
        }

        $new_record = $this->role_service->store($validated);
        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "Role " . Message::CREATED_SUFF,
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

        $data = $this->role_service->show($id);

        // missing model
        if($this->role_service->status === 404)
            $response = [
                "status" => RespStat::NOT_FOUND,
                "message" => "Role " . Message::NOT_FOUND_SUFF
            ];
        else
            $response = [
                "status" => RespStat::SUCCESS,
                "message" => Message::SUCCESS,
                "data" => $data
            ];

        return response()->json($response, $this->role_service->status);
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
            'name' => 'required|min:1|max:25|unique:roles,name,' . $id,
            'level' => 'required|integer',
        ]);

        if(!Gate::allows('is-admin')) {
            return response()->json([
                "status" => RespStat::UNAUTHORIZED,
                "message" => Message::UNAUTHORIZED
            ], 401);
        }

        $data = $this->role_service->update($validated, $id);

        if ($this->role_service->status === 404)
            $response = array(
                "status" => RespStat::NOT_FOUND,
                "message" => "Role " . Message::NOT_FOUND_SUFF
            );
        else
            $response = array(
                "status" => RespStat::SUCCESS,
                "message" => "Role " . Message::UPDATED_SUFF,
                "data" => $data
            );

        return response()->json($response, $this->role_service->status);
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

       $this->role_service->destroy($id);

        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "Role " . Message::UPDATED_SUFF
        );
        return response()->json($response, $this->role_service->status);
    }
}
