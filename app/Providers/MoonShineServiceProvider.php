<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\PangkatGolonganResource;
use App\MoonShine\Resources\MataPelajaranResource;
use App\MoonShine\Resources\PegawaiResource;
use App\MoonShine\Resources\RiwayatPendidikanResource;
use App\MoonShine\Resources\RiwayatJabatanResource;
use App\MoonShine\Resources\RiwayatPangkatResource;
use App\MoonShine\Resources\PelatihanResource;
use App\MoonShine\Resources\KeahlianPegawaiResource;
use App\MoonShine\Resources\KompetensiGuruResource;
use App\MoonShine\Resources\CutiResource;
use App\MoonShine\Resources\MutasiResource;
use App\MoonShine\Resources\PensiunResource;
use App\MoonShine\Resources\PerjalananDinasResource;
use App\MoonShine\Resources\SuratMasukResource;
use App\MoonShine\Resources\SuratKeluarResource;
use App\MoonShine\Resources\ArsipDokumenResource;
use App\MoonShine\Pages\LaporanPegawaiPage;
use App\MoonShine\Pages\LaporanCutiPage;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        // $config->authEnable();

        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                PangkatGolonganResource::class,
                MataPelajaranResource::class,
                PegawaiResource::class,
                RiwayatPendidikanResource::class,
                RiwayatJabatanResource::class,
                RiwayatPangkatResource::class,
                PelatihanResource::class,
                KeahlianPegawaiResource::class,
                KompetensiGuruResource::class,
                CutiResource::class,
                MutasiResource::class,
                PensiunResource::class,
                PerjalananDinasResource::class,
                SuratMasukResource::class,
                SuratKeluarResource::class,
                ArsipDokumenResource::class,
            ])
            ->pages([
                ...$config->getPages(),
                LaporanPegawaiPage::class,
                LaporanCutiPage::class,
            ])
        ;
    }
}
