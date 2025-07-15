<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\Laravel\Layouts\CompactLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\UI\Components\{Breadcrumbs,
    Components,
    Layout\Flash,
    Layout\Div,
    Layout\Body,
    Layout\Burger,
    Layout\Content,
    Layout\Footer,
    Layout\Head,
    Layout\Favicon,
    Layout\Assets,
    Layout\Meta,
    Layout\Header,
    Layout\Html,
    Layout\Layout,
    Layout\Logo,
    Layout\Menu,
    Layout\Sidebar,
    Layout\ThemeSwitcher,
    Layout\TopBar,
    Layout\Wrapper,
    When};
use App\MoonShine\Resources\PangkatGolonganResource;
use MoonShine\MenuManager\{MenuItem, MenuGroup};
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
use App\MoonShine\Resources\{MoonShineUserResource,MoonShineUserRoleResource};
use App\MoonShine\Pages\{
    Dashboard,
    LaporanPegawaiPage,
    LaporanCutiPage,
    LaporanMutasiPage,
    LaporanPensiunPage,
    LaporanPerjalananDinasPage,
    LaporanSuratMasukPage,
    LaporanSuratKeluarPage
};

final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function getFaviconComponent(): Favicon
    {
        return parent::getFaviconComponent()->customAssets([
            'apple-touch' => 'logo-icon.ico',
            '32' => 'logo-icon.ico',
            '16' => 'logo-icon.ico',
            'safari-pinned-tab' => 'logo-icon.ico',
            'web-manifest' => 'logo-icon.ico',
        ]);
    }

    protected function menu(): array
    {
        return [
            MenuItem::make('Dashboard', Dashboard::class)->icon('home'),
            // Grup Kepegawaian
            MenuGroup::make('Kepegawaian', [
                MenuItem::make('Pegawai', PegawaiResource::class)->icon('user-group'),
                MenuItem::make('Riwayat Pendidikan', RiwayatPendidikanResource::class)->icon('document-text'),
                MenuItem::make('Riwayat Jabatan', RiwayatJabatanResource::class)->icon('briefcase'),
                MenuItem::make('Riwayat Pangkat', RiwayatPangkatResource::class)->icon('arrow-trending-up'),
                MenuItem::make('Pelatihan', PelatihanResource::class)->icon('presentation-chart-line'),
                MenuItem::make('Keahlian Pegawai', KeahlianPegawaiResource::class)->icon('light-bulb'),
                //MenuItem::make('Kompetensi Guru', KompetensiGuruResource::class)->icon('clipboard-document-check'),
            ])->icon('user-group'),

            // Grup Permohonan
            MenuGroup::make('Permohonan', [
                MenuItem::make('Cuti', CutiResource::class)->icon('calendar-days'),
                MenuItem::make('Mutasi', MutasiResource::class)->icon('arrow-path'),
                MenuItem::make('Pensiun', PensiunResource::class)->icon('building-office'),
                MenuItem::make('Perjalanan Dinas', PerjalananDinasResource::class)->icon('truck'),
            ])->icon('document-arrow-up'),

            // Grup Surat
            MenuGroup::make('Persuratan & Arsip', [
                MenuItem::make('Surat Masuk', SuratMasukResource::class)->icon('inbox'),
                MenuItem::make('Surat Keluar', SuratKeluarResource::class)->icon('paper-airplane'),
            ])->icon('inbox'),

            MenuGroup::make('Laporan',[
                MenuItem::make('Laporan Pegawai', LaporanPegawaiPage::class)
                    ->icon('users'),
                MenuItem::make('Laporan Cuti', LaporanCutiPage::class)
                    ->icon('document'),
                MenuItem::make('Laporan Mutasi', LaporanMutasiPage::class)
                    ->icon('document'),
                MenuItem::make('Laporan Pensiun', LaporanPensiunPage::class)
                    ->icon('document'),

                MenuItem::make('Laporan Perjalanan Dinas', LaporanPerjalananDinasPage::class)
                    ->icon('document'),
                MenuItem::make('Laporan Surat Masuk', LaporanSuratMasukPage::class)
                    ->icon('document'),
                MenuItem::make('Laporan Surat Keluar', LaporanSuratKeluarPage::class)
                    ->icon('document'),
            ])->icon('document-chart-bar'),

            MenuGroup::make('Referensi Data', [
                MenuItem::make('Pangkat & Golongan', PangkatGolonganResource::class)->icon('academic-cap'),
                MenuItem::make('Mata Pelajaran', MataPelajaranResource::class)->icon('book-open'),
            ])->icon('folder'),

            MenuGroup::make('System', [
                MenuItem::make('User', MoonShineUserResource::class)
                    ->icon('users'),
                MenuItem::make('Roles', MoonShineUserRoleResource::class)
                    ->icon('shield-check'),
            ])->icon('cog-6-tooth'),

            //...parent::menu(),
        ];
    }


    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();
    }

    protected function getFooterMenu(): array
    {
        return [
            'https://sman2banjarmasin.sch.id/' => 'SMAN 2 Banjarmasin',
        ];
    }
 
    protected function getFooterCopyright(): string
    {
        return 'SIMPEG - Sistem Informasi Kepegawaian';
    }
}
