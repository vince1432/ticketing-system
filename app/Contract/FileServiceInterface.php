<?php

namespace App\Contract;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

interface FileServiceInterface
{
    /**
     * add file to storage and database
     *
     * @param  UploadedFile $file file from request
     * @param  User|Ticket|Model $model owner model
     * @return array
     */
    public function store(UploadedFile $uploaded_file, User|Ticket|Model $model) : array;
}
