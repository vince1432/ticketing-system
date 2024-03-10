<?php

namespace App\Http\Controllers;

use App\Contract\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user_service;

    /**
     * set service to be used.
     *
     * @param  App\Contract\UserServiceInterface  $user_service
     * @return void
     */
    public function __construct( UserServiceInterface $user_service)
    {
        $this->user_service = $user_service;
        // $this->model = User::class;
        // $this->model_string = "User";
        // // set base controller service
        // $this->setService($this->user_service);
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

        $response = $this->user_service->index($item_count);
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
            'name' => 'max:255',
            'email' => 'required|unique:users,email|max:255|email',
            'password' => 'required|min:6|max:50|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'roles' => 'required|array|min:1',
            'roles.*' => 'required|integer|distinct|exists:roles,id',
        ]);

        // if(request()->user()->cannot('viewAny', Module::class)) {
        //     return response()->json([
        //         "status" => "Unauthorized",
        //         "message" => "You can't access this information."
        //     ], 401);
        // }

        $new_record = $this->user_service->store($validated);
        $response = array(
            "status" => "Success",
            "message" => "User successfuly created.",
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
        $data = $this->user_service->show($id);

        // missing model
        if($this->user_service->status === 404)
            $response = [
                "status" => "Not found",
                "message" => "User not found."
            ];
        // // unauthorize access
        // else if(request()->user()->cannot('view', new Module($data))) {
        //     $response =[
        //         "status" => "Unauthorized",
        //         "message" => "You can't access this information."
        //     ];
        //     $this->user_service->status = 401;
        // }
        else
            $response = [
                "status" => "Success",
                "message" => "Success.",
                "data" => $data
            ];

        return response()->json($response, $this->user_service->status);
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
            'name' => 'nullable|max:255',
            'email' => 'nullable|max:255|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|max:50|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'required|integer|distinct|exists:roles,id',
        ]);

        $data = $this->user_service->update($validated, $id);

        if ($this->user_service->status === 404)
            $response = array(
                "status" => "Not found",
                "message" => "User not found."
            );
        else
            $response = array(
                "status" => "Success",
                "message" => "User successfuly updated.",
                "data" => $data
            );

        return response()->json($response, $this->user_service->status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $this->user_service->destroy($id);

        $response = array(
            "status" => "Success",
            "message" => "User successfuly removed."
        );
        return response()->json($response, $this->user_service->status);
    }
}
