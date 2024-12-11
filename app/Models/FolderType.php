<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FolderType extends Model
{
    use HasFactory, SoftDeletes;

    CONST FILE = "fail";
    CONST URL = "url";

    protected $guarded = [];

    public function folders()
    {
        return $this->belongsToMany(Folder::class, 'folder_folder_types');
    }
}
