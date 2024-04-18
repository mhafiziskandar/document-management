<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class File extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    const PENDING = "Semakan";
    const REJECTED = "Ditolak";
    const APPROVED = "Diterima";

    const PUBLIC = "Umum";
    const PRIVATE = "Sulit";

    const DRAFT = "Draf";
    const ACTIVE = "Aktif";

    const PRIMARY = "Primer";
    const SECONDARY = "Sekunder";

    const YES = 1;
    const NO = 0;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->setDescriptionForEvent(function ($eventName) {
                if ($eventName == 'created') {
                    return "Fail baru ditambah";
                } else if ($eventName == 'deleted') {
                    return "Fail dihapus";
                } else if ($eventName == 'updated') {
                    return "Fail ";
                }
                else if ($eventName == 'restored') {
                    return "Fail dikembalikan";
                }
                else {
                    return $eventName;
                }
            });
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'file_categories');
    }

    public function type()
    {
        return $this->belongsTo(FolderType::class, 'folder_type_id')->withTrashed();
    }
}
