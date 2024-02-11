<?php

namespace App\Services;

use App\Contract\FileRepositoryInterface;
use App\Contract\FileServiceInterface;
use App\Models\File;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Image;

class FileService implements FileServiceInterface
{
    private $file_repository;
    // response status
    public int $status = 200;
    public string $error = "";

    public function __construct(FileRepositoryInterface $file_repository) {
        $this->file_repository = $file_repository;
    }

    public function store(UploadedFile $uploaded_file, User|Ticket|Model $model) : array
    {
       try {
            $prep_file = $this->prepare($uploaded_file, $model);

            if($prep_file['prep_file']) {
                Storage::disk($prep_file['disk'])->put($prep_file['url'], $prep_file['prep_file'], 'public');

                $new_file = $this->file_repository->insert($model, $prep_file);
                return $new_file->toArray();
            }
        } catch (\Exception $e) {
                $this->status = 500;
                $this->error = $e->getMessage();
        }

        // return on error
        return [];
    }

    public function update(File $file, UploadedFile $uploaded_file, User|Ticket|Model $model)
    {
        try {
            $prep_file = $this->prepare($uploaded_file, $model);

            if($prep_file['prep_file']) {
                Storage::disk($prep_file['disk'])->put($prep_file['url'], $prep_file['prep_file'], 'public');

                $new_file = $this->file_repository->insert($model, $prep_file);
                return $new_file->toArray();
            }
        } catch (\Exception $e) {
                $this->status = 500;
                $this->error = $e->getMessage();
        }

        // return on error
        return [];
    }

    public function destroy(File $file) {
        if($this->file_repository->delete($file))
            Storage::delete($file->url);
        return $file;
    }

    // prepare file using intervention image
    private function prepare(UploadedFile $uploaded_file, User|Ticket|Model $model) : array
    {
       try {
            $fileName = time() . '.' . $uploaded_file->getClientOriginalExtension();
            $prep_file = Image::make($uploaded_file->getRealPath());
            $prep_file->resize(120, 120, function ($constraint) {
                $constraint->aspectRatio();
            });

            $prep_file->stream(); // <-- Key point

            if(get_class($model) === 'Ticket') {
                $disk = "local";
                $path = "attachment/{$model->id}_{$fileName}";
            }
            else {
                $disk = "public";
                $path = "profile/{$model->id}_{$fileName}";
            }

            return [
                "name" => $uploaded_file->getClientOriginalName(),
                "filetype" => $uploaded_file->getClientMimeType(),
                "size" => $uploaded_file->getSize(),
                "url" => $path,
                "disk" => $disk,
                "prep_file" => $prep_file,
            ];
       } catch (\Exception $e) {
            $this->status = 500;
            $this->error = $e->getMessage();

            return [
                "name" => NULL,
                "filetype" => NULL,
                "size" => NULL,
                "url" => NULL,
                "disk" => NULL,
                "prep_file" => NULL,
            ];
       }
    }
}
