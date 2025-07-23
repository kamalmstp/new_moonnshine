<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai; // Import model Pegawai
use MoonShine\Laravel\Models\MoonshineUser; // Import MoonshineUser model Anda
use MoonShine\Laravel\Models\MoonshineUserRole; // Import MoonshineUserRole model Anda
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use MoonShine\MoonShine; // Import MoonShine facade untuk redirect

class UserController extends Controller
{
    /**
     * Menampilkan form untuk membuat user dari data pegawai.
     * (Opsional: Anda bisa membuat view terpisah jika tidak ingin di-handle di modal Moonshine)
     * Untuk kasus ini, kita akan langsung memprosesnya jika dipanggil oleh action button.
     *
     * @param Request $request
     * @param int $pegawaiId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showCreateUserForm(Request $request, int $pegawaiId)
    {
        $pegawai = Pegawai::with('user')->findOrFail($pegawaiId);

        return redirect(MoonShine::getResource('pegawai')->route('index'))
            ->with('error', 'Akses langsung ke form ini tidak disarankan. Gunakan tombol di daftar Pegawai.');
    }


    /**
     * Memproses pembuatan user dari data pegawai.
     *
     * @param Request $request
     * @param int $pegawaiId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUserFromPegawai(Request $request, int $pegawaiId)
    {
        $pegawai = Pegawai::with('user')->findOrFail($pegawaiId);

        $request->validate([
            // Jika Anda mengirim selected_role via URL atau hidden input
            'selected_role_id' => ['required', 'integer', 'exists:moonshine_user_roles,id'],
            // Jika Anda ingin user memasukkan password di halaman terpisah
            'password' => ['nullable', 'string', 'min:8'], // Nullable karena kita bisa generate otomatis
        ]);

        $selectedRoleId = $request->input('selected_role_id');
        $inputPassword = $request->input('password');

        // Pastikan pegawai belum memiliki akun pengguna dan email tidak kosong
        if ($pegawai->user_id || empty($pegawai->email) || MoonshineUser::where('email', $pegawai->email)->exists()) {
            return back()->with('error', 'Gagal membuat akun pengguna: Akun sudah ada atau data tidak valid.');
        }

        DB::beginTransaction();
        try {
            $password = $inputPassword ? Hash::make($inputPassword) : Hash::make(Str::random(10)); // Generate atau gunakan password input
            $generatedPassword = $inputPassword ?: Str::random(10); // Simpan password yang digenerate untuk pesan

            $user = MoonshineUser::create([
                'name' => $pegawai->nama_lengkap,
                'email' => $pegawai->email,
                'password' => $password,
                'moonshine_user_role_id' => $selectedRoleId,
                'avatar' => null,
            ]);

            $pegawai->update(['user_id' => $user->id]);

            DB::commit();

            session()->flash('success', "Akun pengguna untuk {$pegawai->nama_lengkap} berhasil dibuat. Email: {$user->email}, Password: {$generatedPassword}.");
            return redirect(MoonShine::getResource('pegawai')->route('index'));

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error("Gagal membuat akun pengguna dari pegawai: " . $e->getMessage(), ['pegawai_id' => $pegawaiId, 'exception' => $e]);
            return back()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }
}
