<?php

namespace App\Contract;

interface BaseRepositoryInterface
{
    public function all($count = 0, $filters = []);

    public function get($id);

    public function insert($data);

    public function update($data, $id);

    public function delete($id);

    public function exist($id);
}
