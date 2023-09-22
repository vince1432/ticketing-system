<?php

namespace App\Contract;

interface BaseRepositoryInterface
{
    /**
     * data list
     *
     * @param  integer $count item count to return
     * @param  array $filters (optional) for search
     * @param  string $col (optional) column name for order
     * @param  string $dir (optional) order direction
     * @return Illuminate\Database\Eloquent\Collection data list
     */
    public function all($count = 0, $filters = [], $col = 'id', $dir = 'asc');

    /**
     * get record by id
     *
     * @param  integer $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function get($id);

    /**
     * add new record
     *
     * @param  array $data record data
     * @return Illuminate\Database\Eloquent\Model
     */
    public function insert($data);

    /**
     * update record
     *
     * @param  array $data record data
     * @param  integer $id record id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($data, $id);

    /**
     * delete
     *
     * @param  integer $id record id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function delete($id);

    /**
     * check if record exist
     *
     * @param  integer $id record id
     * @return integer
     */
    public function exist($id);
}
