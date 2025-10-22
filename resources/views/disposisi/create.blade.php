@php
    $riwayat = $item->disposisiHistory ?? collect(); 
    
    $penerima = \App\Models\User::where('role', 'staff_tu')->pluck('name', 'id')->toArray();
    
    if (empty($penerima)) {
        $penerima = [
            1 => 'Staff TU Andi',
            2 => 'Staff TU Budi',
        ];
    }
@endphp

<div class="space-y-6">

    <x-moonshine::box class="bg-blue-50/70 p-4 border-l-4 border-blue-500">
        <h3 class="text-xl font-bold">Surat: {{ $item->nomor_surat }}</h3>
        <p class="text-sm">Perihal: {{ $item->perihal }}</p>
    </x-moonshine::box>

    <x-moonshine::box title="Riwayat Disposisi" class="shadow-md">
        @forelse($riwayat as $disposisi)
            <div class="mb-4 p-3 border rounded-lg bg-gray-50/70">
                <div class="flex justify-between text-xs text-gray-500">
                    {{-- Asumsi Model Disposisi punya relasi pengirim --}}
                    <span>Dari: {{ $disposisi->pengirim->name ?? 'Kepala Sekolah' }}</span>
                    <span>Tgl: {{ optional($disposisi->tanggal_disposisi)->format('d/m/Y H:i') ?? 'N/A' }}</span>
                </div>
                {{-- Asumsi Model Disposisi punya relasi penerima --}}
                <p class="font-medium mt-1 text-sm">Kepada: {{ $disposisi->penerima->name ?? 'Staff TU' }}</p>
                <p class="text-base italic mt-1">"{{ $disposisi->isi_disposisi }}"</p>
                <span class="text-xs font-bold @if(Str::lower($disposisi->status) == 'selesai') text-green-600 @else text-red-600 @endif">
                    Status: {{ $disposisi->status }}
                </span>
            </div>
        @empty
            <x-moonshine::alert type="default">
                Belum ada riwayat disposisi untuk surat ini.
            </x-moonshine::alert>
        @endforelse
    </x-moonshine::box>

    <x-moonshine::box title="Buat Instruksi Disposisi Baru">
        <x-moonshine::form
            action="{{ route('disposisi.store') }}" {{-- **Ganti dengan Route POST Anda yang sebenarnya** --}}
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            
            {{-- ID Surat Masuk (Hidden) --}}
            <input type="hidden" name="surat_masuk_id" value="{{ $item->id }}">
            
            {{-- Tujuan Disposisi (Penerima) --}}
            <x-moonshine::form.label for="penerima_id">Tujuan Disposisi (Penerima Instruksi)</x-moonshine::form.label>
            <x-moonshine::form.select
                name="penerima_id"
                id="penerima_id"
                :values="$penerima"
                required
                class="mt-1"
            />

            {{-- Isi Disposisi (Instruksi) --}}
            <x-moonshine::form.label for="isi_disposisi" class="mt-4">Instruksi Disposisi (Perintah)</x-moonshine::form.label>
            <x-moonshine::form.textarea
                name="isi_disposisi"
                id="isi_disposisi"
                required
                rows="4"
                placeholder="Contoh: Mohon arsipkan surat ini dan buatkan balasan formal."
                class="mt-1"
            />

            <x-moonshine::form.button type="submit" class="mt-4">
                <i class="heroicons.outline.paper-airplane"></i> Kirim Disposisi
            </x-moonshine::form.button>
        </x-moonshine::form>
    </x-moonshine::box>

</div>