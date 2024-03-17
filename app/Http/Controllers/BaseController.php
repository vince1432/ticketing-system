<?php

namespace App\Http\Controllers;

use App\Constants\Message;
use App\Constants\RespStat;
use App\Models\User;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    private object $base_service;
    public string $model;
    public string $model_string;

    /**
     * set class service
     *
     * @param  ServiceInterface $base_service
     * @return void
     */
    public function setService(&$base_service)
    {
        $this->base_service =& $base_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->user()->cannot('viewAny', $this->model)) {
            return response()->json([
                "status" => RespStat::UNAUTHORIZED,
                "message" => Message::UNAUTHORIZED
            ], 401);
        }

        $query_params = request()->query();
        $item_count = $query_params['item_count'] ?? 10;

        $response = $this->base_service->index($item_count);
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
        if(request()->user()->cannot('viewAny', $this->model)) {
            return response()->json([
                "status" => RespStat::UNAUTHORIZED,
                "message" => Message::UNAUTHORIZED
            ], 401);
        }

        $new_record = $this->base_service->store($request);
        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "{$this->model_string} " . Message::CREATED_SUFF,
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
        $data = $this->base_service->show($id);

        // missing model
        if($this->base_service->status === 404)
            $response = [
                "status" => RespStat::NOT_FOUND,
                "message" => Message::NOT_FOUND_SUFF
            ];
        // unauthorize access
        else if(request()->user()->cannot('view', new User($data))) {
            $response =[
                "status" => RespStat::UNAUTHORIZED,
                "message" => Message::UNAUTHORIZED
            ];
            $this->base_service->status = 401;
        }
        else
            $response = [
                "status" => RespStat::SUCCESS,
                "message" => Message::SUCCESS,
                "data" => $data
            ];

        return response()->json($response, $this->base_service->status);
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
        $data = $this->base_service->update($request, $id);

        if ($this->base_service->status === 404)
            $response = array(
                "status" => RespStat::NOT_FOUND,
                "message" => "{$this->model_string} " . Message::NOT_FOUND_SUFF
            );
        else
            $response = array(
                "status" => RespStat::SUCCESS,
                "message" => "{$this->model_string} " . Message::UPDATED_SUFF,
                "data" => $data
            );

        return response()->json($response, $this->base_service->status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $this->base_service->destroy($id);

        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => "{$this->model_string} " . Message::REMOVED_SUFF
        );
        return response()->json($response, $this->base_service->status);
    }
}
