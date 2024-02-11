<?php

namespace App\Contract;

use App\Models\File;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface FileRepositoryInterface
{
    /**
     * add new File
     *
     * @param  User|Ticket|Model {
     * id -> int - id of model
     * } $model parent model
     * @param  array[
     * name => string file name with extension,
     * filetype => string  - file type,
     * size => int  - file size,
     * url => string  - file path
     * ] $data File data
     * @return App\Models\File
     */
    public function insert(User|Ticket|Model $model, array $data) : File;

    /**
     * update
     *
     * @param  File $file
     * @param  array[
     * name => string,
     * filetype => string,
     * size => int,
     * url => string,
     * ] $data file data
     * @return File file object
     */
    public function update(File $file, array $data) : File;

    /**
     * delete file
     *
     * @param File $file
     * @return bool success/fail
     */
    public function delete(File $file) : bool;

    /**
     * check if file exist
     *
     * @param  File $file
     * @return int
     */
    public function exist(File $file) : int;
}
