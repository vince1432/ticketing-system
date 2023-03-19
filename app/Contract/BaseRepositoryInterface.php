<?php

namespace App\Contract;

interface BaseRepositoryInterface
{
    public function all();

    public function get($id);

    public function insert($id);

    public function update($id);

    public function delete($id);
}
