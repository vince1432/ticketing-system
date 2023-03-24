<?php

namespace App\Contract;

use Illuminate\Http\Request;

interface BaseServiceInterface
{
    public function index($count = 10);

    public function show($id);

    public function store(Request $request);

    public function update(Request $request, $id);

    public function destroy($id);
}
