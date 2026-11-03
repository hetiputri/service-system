<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';
    protected $table = 'tbl_pengguna';
    protected $primaryKey = 'id_user';
    protected $keyType = 'integer';

    protected $fillable = [
        'id_user',
        'nama',
        'username',
        'password',
        'pertanyaan',
        'jawaban',
        'role',
        'modul',
        'is_login',
        'id_skpd',
        'id_pegawai',
        'login_terakhir',
        'id_skpd',
        'id_pegawai',
        'is_penanggung_jawab',
        'ms_level_id',
        'passwordhash',
        'android_id',
        'status_login',
        'is_android_dev',
        'is_admin',
        'generate_dashboard',
        'id_user_relasi',
        'android_id_v2'

    ];

    public $incrementing = true;
    public $timestamps = true;

    public static function createorupdate($data)
    {
        $record = self::where('id_user', $data['id_user'])
            ->where('id_pegawai', $data['id_pegawai']);
        if ($record->count() > 0) {
            $record = $record->first();
            $record->update($data);
            return 'berhasil update';
        } else {
            if ($data['id_pegawai'] > 0 && $data['id_user'] > 0) {
                self::create($data);
                return 'berhasil create';
            }
        }
    }
}
