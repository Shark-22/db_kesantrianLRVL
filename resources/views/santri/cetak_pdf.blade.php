<!DOCTYPE html>
<html>
<head>
    <title>Laporan Santri - {{ $santri->nama }}</title>
    <style>
        @page { margin-top: 0px; margin-bottom: 30px; margin-left: 30px; margin-right: 30px; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; margin: 0; padding: 0; }
        
        /* Kop Surat */
        .header-banner { width: 100%; margin-bottom: 20px; text-align: center; }
        .header-banner img { width: 100%; height: auto; display: block; }
        
        /* Biodata */
        .biodata { margin-bottom: 20px; width: 100%; margin-top: 10px; }
        .biodata td { padding: 3px; vertical-align: top; }
        
        /* Tabel Data */
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-data th, .table-data td { border: 1px solid black; padding: 6px; font-size: 11pt; text-align: left; }
        .table-data th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        
        /* Helper Classes */
        .text-center { text-align: center !important; }
        .text-uppercase { text-transform: uppercase; }
        
        /* Area Tanda Tangan */
        .ttd-image { 
            height: 70px;       
            width: auto;            
            max-width: 80%;     
            margin: 5px auto;   
            display: block;
        }
    </style>
</head>
<body>
    <?php
        // 1. Gambar Kop Surat
        $pathKop = public_path('KOP.png');
        $base64Kop = file_exists($pathKop) ? 'data:image/png;base64,' . base64_encode(file_get_contents($pathKop)) : '';
        
        // 2. TTD Kanan (Kepala Kesantrian - existing)
        $pathTtd = public_path('TTD.png');
        $base64Ttd = file_exists($pathTtd) ? 'data:image/png;base64,' . base64_encode(file_get_contents($pathTtd)) : '';

        // 3. TTD Kiri (Kepala Unit - BARU)
        // Pastikan Anda menaruh file 'ttd_kepala_unit.png' di folder public
        $pathTtdUnit = public_path('TTD-Achid.png');
        $base64TtdUnit = file_exists($pathTtdUnit) ? 'data:image/png;base64,' . base64_encode(file_get_contents($pathTtdUnit)) : '';
    ?>

    <div class="header-banner">
        @if($base64Kop) <img src="{{ $base64Kop }}"> @endif
    </div>

    <h3 style="text-align: center; text-decoration: underline;">LAPORAN REKAPITULASI KESANTRIAN</h3>

    <table class="biodata">
        <tr><td width="130">Nama Santri</td><td width="10">:</td><td><strong>{{ $santri->nama }}</strong></td></tr>
        <tr><td>NIS</td><td>:</td><td>{{ $santri->nis }}</td></tr>
        <tr><td>Asrama / Angk</td><td>:</td><td>{{ $santri->asrama }} (Angkatan {{ $santri->angkatan }})</td></tr>
    </table>

    <h4>A. Riwayat Pelanggaran </h4>
    <table class="table-data">
        <thead>
            <tr><th width="30">No</th><th width="100">Tanggal</th><th>Kasus</th><th width="100">Level</th></tr>
        </thead>
        <tbody>
            @forelse($santri->violations as $v)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($v->tanggal)->format('d-m-Y') }}</td>
                <td>
                    <strong>{{ $v->jenis_pelanggaran ?? $v->jenis ?? $v->kasus ?? '-' }}</strong><br>
                    {{-- <small style="color: gray; font-style: italic;">Catatan: {{ $v->keterangan ?? $v->catatan ?? '-' }}</small> 
                    aktifkan ini jika ingin menampilkan keterangan--}}
                </td>
                <td class="text-center text-uppercase">{{ $v->tingkat ?? $v->level }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center"><i>Bersih.</i></td></tr>
            @endforelse
        </tbody>
    </table>

    <h4>B. Riwayat Prestasi </h4>
    <table class="table-data">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="100">Tanggal</th>
                <th>Prestasi</th>
                <th width="100">Tingkat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($santri->achievements as $p)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}</td>
                <td>
                    <strong>{{ $p->nama_prestasi ?? $p->judul ?? $p->prestasi ?? '-' }}</strong><br>
                    {{-- <small style="color: gray; font-style: italic;">
                        Catatan: {{ $p->keterangan ?? $p->catatan ?? '-' }}
                    </small> aktifkan ini jika ingin menampilkan keterangan --}}
                </td>
                <td class="text-center text-uppercase">
                    {{ $p->tingkat ?? $p->level ?? '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center"><i>Belum ada data prestasi.</i></td>
            </tr>
            @endforelse
        </tbody>
    </table>

   <table style="width: 100%; margin-top: 40px; text-align: center;">
        <tr>
            <td width="50%" valign="top">
                <p style="margin-bottom: 5px;">Mengetahui,<br>Kepala Unit MAQDA</p>
                
                <div style="height: 80px; display: flex; align-items: center; justify-content: center;">
                    @if($base64TtdUnit) 
                        <img src="{{ $base64TtdUnit }}" style="height: 75px; width: auto;"> 
                    @endif
                </div>

                <p style="margin-top: 5px;"><strong>( Ust Achid Muhtarom )</strong></p>
            </td>

            <td width="50%" valign="top">
                <p style="margin-bottom: 5px;">Magelang, {{ date('d F Y') }}<br>Kepala Kesantrian</p>
                
                <div style="height: 80px; display: flex; align-items: center; justify-content: center;">
                    @if($base64Ttd) 
                        <img src="{{ $base64Ttd }}" style="height: 75px; width: auto;"> 
                    @endif
                </div>

                <p style="margin-top: 5px;"><strong>( Ust Ridho Rizky )</strong></p>
            </td>
        </tr>
    </table>
</body>
</html>