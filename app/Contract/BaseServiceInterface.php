<?php

namespace App\Contract;

use Illuminate\Http\Request;

interface BaseServiceInterface
{
    public function index(int $count = 0);

    public function show(int $id);

    /**
     * store
     * Store roles
     * @param  mixed $validated
     * @return void
     */
    public function store(array $validated);

    public function update(array $validated, $id);

    public function destroy($id);
}
