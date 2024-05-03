<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Folder extends Model
{
    use HasFactory, HasSlug, SoftDeletes, LogsActivity;

    protected $guarded = [];

    const COMPLETE = "Lengkap";
    const INCOMPLETE = "Tidak Lengkap";

    const ONTIME = "Menepati Masa";
    const INPROGRESS = "Dalam Masa";
    const OVERDUE = "Lewat";

    const YA = 1;
    const TIDAK = 0;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {
                if ($eventName == 'updated') {
                    return "Projek dikemaskini";
                } else if ($eventName == 'created') {
                    return "Projek dibuat";
                } else if ($eventName == 'deleted') {
                    return "Projek dihapus";
                } else if ($eventName == 'restored') {
                    return "Projek dikembalikan";
                } else {
                    return $eventName;
                }
            });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('project_name')
            ->saveSlugsTo('slug');
    }

    protected static function booted()
    {
        static::created(function ($model) {
            $model->bil = (string) $model->id . "/" . $model->year;
            $model->save();
        });
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone("Asia/Kuala_Lumpur")->format("j M Y, g:i A");
    }

    public function getTarikhAkhirAttribute($value)
    {
        return Carbon::parse($value)->setTimezone("Asia/Kuala_Lumpur")->format("d/m/Y");
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function fileTrashed()
    {
        return $this->hasMany(File::class)->onlyTrashed();
    }

    public function fileWithTrashed()
    {
        return $this->hasMany(File::class)->withTrashed();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_folders');
    }

    public function types()
    {
        return $this->belongsToMany(FolderType::class, 'folder_folder_types')->withTrashed();
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class)->withTrashed();
    }

    public function folderable()
    {
        return $this->morphTo();
    }

    public function departments()
    {
        return $this->morphedToMany(Department::class, 'folderable');
    }

    public function folders()
    {
        return $this->morphToMany(Folder::class, 'folderable');
    }
}
