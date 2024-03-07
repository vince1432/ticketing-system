<?php

namespace App\Http\Controllers;

use App\Constants\Message;
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
                "status" => "Unauthorized",
                "message" => "You can't access this information."
            ], 401);
        }

        $query_params = request()->query();
        $item_count = $query_params['item_count'] ?? 10;

        $response = $this->base_service->index($item_count);
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
        if(request()->user()->cannot('viewAny', $this->model)) {
            return response()->json([
                "status" => "Unauthorized",
                "message" => "You can't access this information."
            ], 401);
        }

        $new_record = $this->base_service->store($request);
        $response = array(
            "status" => "Success",
            "message" => "{$this->model_string} successfuly created.",
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
                "status" => "Not found",
                "message" => "{$this->model_string} not found."
            ];
        // unauthorize access
        else if(request()->user()->cannot('view', new User($data))) {
            $response =[
                "status" => "Unauthorized",
                "message" => "You can't access this information."
            ];
            $this->base_service->status = 401;
        }
        else
            $response = [
                "status" => "Success",
                "message" => "Success.",
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
                "status" => "Not found",
                "message" => "{$this->model_string} not found."
            );
        else
            $response = array(
                "status" => "Success",
                "message" => "{$this->model_string} successfuly updated.",
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
            "status" => "Success",
            "message" => "{$this->model_string} successfuly removed."
        );
        return response()->json($response, $this->base_service->status);
    }
}
