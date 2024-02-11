<?php

namespace App\Repositories;

use App\Contract\FileRepositoryInterface;
use App\Models\File;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class FileRepository implements FileRepositoryInterface
{
    public function insert(User|Ticket|Model $model, array $data) : File
    {
        $new_file = $model->fileable()->create( [
                "name" => $data["name"],
                "filetype" => $data["filetype"],
                "size" => $data["size"],
                "url" => $data["url"],
            ]);

        return $new_file->fileable();
    }

    public function update(File $file, array $data) : File
    {
        $file->update([
            "name" => $data["name"],
            "filetype" => $data["filetype"],
            "size" => $data["size"],
            "url" => $data["url"],
        ]);

        return $file;
    }

    public function delete(File $file) : bool
    {
        return $file->delete();
    }

    public function exist(File $file) : int
    {
        return $file->exist();
    }
}
