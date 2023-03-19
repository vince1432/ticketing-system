<?php

namespace App\Contract;

interface BaseServiceInterface
{
    public function index();

    public function show($id);

    public function store($id);

    public function update($id);

    public function destroy($id);
}
