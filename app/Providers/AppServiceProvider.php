<?php

namespace App\Providers;

use App\Repositories\Cuti\CutiRepo;
use App\Repositories\Cuti\CutiRepoImplement;
use Illuminate\Support\Facades\URL;
use App\Repositories\Filter\FilterRepository;
use App\Repositories\Filter\FilterRepositoryImplement;
use App\Repositories\KonsepMutasi\KonsepMutasiRepo;
use App\Repositories\KonsepMutasi\KonsepMutasiRepoImplement;
use App\Repositories\Otentikasi\AuthRepository;
use App\Repositories\Otentikasi\AuthRepositoryImplement;
use App\Repositories\Pegawai\PegawaiRepo;
use App\Repositories\Pegawai\PegawaiRepoImplement;
use App\Repositories\Integrasi\IntegrasiRepo;
use App\Repositories\Integrasi\IntegrasiRepoImplement;
use App\Repositories\Pensiun\PensiunRepo;
use App\Repositories\Pensiun\PensiunRepoImplement;
use App\Repositories\Referensi\ReferensiRepo;
use App\Repositories\Referensi\ReferensiRepoImplement;
use App\Repositories\RolePermission\RolePermitRepo;
use App\Repositories\RolePermission\RolePermitRepoImplement;
use App\Repositories\Sinkronisasi\SinkronRepo;
use App\Repositories\Sinkronisasi\SinkronRepoImplement;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryImplement;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepository::class,
            UserRepositoryImplement::class
        );
        $this->app->bind(
            FilterRepository::class,
            FilterRepositoryImplement::class
        );
        $this->app->bind(
            AuthRepository::class,
            AuthRepositoryImplement::class
        );
        $this->app->bind(
            RolePermitRepo::class,
            RolePermitRepoImplement::class
        );
        $this->app->bind(
            PensiunRepo::class,
            PensiunRepoImplement::class
        );

        $this->app->bind(
            SinkronRepo::class,
            SinkronRepoImplement::class
        );
        $this->app->bind(
            KonsepMutasiRepo::class,
            KonsepMutasiRepoImplement::class
        );

        $this->app->bind(
            PegawaiRepo::class,
            PegawaiRepoImplement::class
        );

        $this->app->bind(
            ReferensiRepo::class,
            ReferensiRepoImplement::class
        );

        $this->app->bind(
            IntegrasiRepo::class,
            IntegrasiRepoImplement::class
        );

        $this->app->bind(
            CutiRepo::class,
            CutiRepoImplement::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');

        // hanya aktif di production (server)
        if (config('app.env') == 'production') {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }
    }
}
