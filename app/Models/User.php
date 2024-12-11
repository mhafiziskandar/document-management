<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    CONST APPROVED = 'Diterima';
    CONST REJECT = 'Ditolak';
    CONST DELETE = 'Tidak Aktif';
    CONST PENDING = 'Dalam Proses';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'profile_image',
        'ic_no',
        'status',
        'sync',
        'is_email_valid',
        'department_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getSyncAttribute($value)
    {
        return !is_null($value) ? Carbon::parse($value)->setTimezone("Asia/Kuala_Lumpur")->format("j M Y, g:i A") : null;
    }

    public function folders()
    {
        return $this->morphToMany(Folder::class, 'folderable', 'folderables');
    }

    public function extensions()
    {
        return $this->belongsToMany(FileExtension::class, 'user_file_extensions');
    }

    public function checkEmailValidity()
    {
        $validator = Validator::make(
            ["email" => $this->email],
            ["email" => "email:rfc,strict,dns,spoof"]
        );

        $this->update([
            "is_email_valid" => $validator->passes()
        ]);
    }
}