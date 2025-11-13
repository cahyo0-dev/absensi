@extends('layouts.without-sidebar')

@section('title', 'Standard Operating Procedure (SOP)')

@section('styles')
    <style>
        .sop-card {
            transition: all 0.3s ease;
            border-left: 4px solid #3b82f6;
            cursor: pointer;
        }

        .sop-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-book text-blue-600 mr-3"></i>
                    URAIAN TUGAS STASIUN METEOROLOGI KELAS III JUWATA TARAKAN
                </h1>
                <p class="text-gray-600 text-lg">Standar Operasional Prosedur untuk seluruh kegiatan operasional</p>
            </div>

            <!-- SOP Cards -->
            <div class="space-y-6">
                <!-- SOP Forecaster -->
                <div class="sop-card bg-white rounded-lg shadow-md p-6" onclick="showSopDetail('forecaster')">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-cloud-sun text-green-500 mr-3"></i>
                                FORECASTER
                            </h3>
                            <p class="text-gray-600">Klik Untuk Melihat Mraian Tugas Forecaster</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 text-lg"></i>
                    </div>
                </div>

                <!-- SOP Inspeksi -->
                <div class="sop-card bg-white rounded-lg shadow-md p-6" onclick="showSopDetail('Observer')">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-clipboard-check text-blue-500 mr-3"></i>
                                OBSERVER
                            </h3>
                            <p class="text-gray-600">Klik Untuk Melihat Uraian Tugas Observer</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 text-lg"></i>
                    </div>
                </div>

                <!-- SOP Laporan -->
                <div class="sop-card bg-white rounded-lg shadow-md p-6" onclick="showSopDetail('Teknisi')">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-chart-bar text-purple-500 mr-3"></i>
                                TEKNISI
                            </h3>
                            <p class="text-gray-600">Klik Untuk Melihat Uraian Tugas Teknisi</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-yellow-800 mb-2">Penting:</h4>
                        <ul class="text-yellow-700 space-y-1">
                            <li>• Semua prosedur harus dilaksanakan sesuai dengan standar operasional yang berlaku</li>
                            <li>• Setiap perubahan kondisi harus segera dilaporkan kepada atasan langsung</li>
                            <li>• Dokumentasi yang lengkap dan akurat wajib dilakukan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('absensi.form') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200 font-medium shadow-sm">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Isi Absensi
                </a>
                @auth
                    @if (Auth::user()->isPengawas())
                        <a href="{{ route('pengawas.inspeksi') }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 font-medium shadow-sm">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            Form Inspeksi
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 font-medium shadow-sm">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Modal SOP Detail -->
    <div id="sopModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-4 sm:top-20 mx-auto p-4 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[90vh] sm:max-h-[80vh] overflow-y-auto">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4 pb-2 border-b">
                    <h3 class="text-lg font-medium text-gray-900" id="sopModalTitle">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Detail SOP
                    </h3>
                    <button onclick="closeSopModal()" class="text-gray-400 hover:text-gray-600 transition duration-150">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="sopModalContent">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showSopDetail(type) {
            const modal = document.getElementById('sopModal');
            const title = document.getElementById('sopModalTitle');
            const content = document.getElementById('sopModalContent');

            let sopData = {};

            switch (type) {
                case 'forecaster':
                    sopData = {
                        title: 'SOP FORECASTER',
                        icon: 'fa-cloud-sun',
                        color: 'green',
                        content: `
                            <h4 class="font-semibold text-gray-800 mb-4 text-lg border-b pb-2">Uraian Tugas Forecaster:</h4>
                            <div>
                                <h5 class="font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-tasks text-green-500 mr-2"></i>
                                    Tugas Pokok
                                </h5>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan pengumpulan berbagai produk informasi cuaca, termasuk data hasil model prediksi komputer (Numerical Weather Prediction/NWP) dan informasi peringatan dini yang diterbitkan oleh BMKG Pusat.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Membuat prakiraan cuaca (prakicu) serta melaksanakan penyebaran data dan informasi cuaca kelautan yang komprehensif untuk seluruh wilayah perairan Provinsi Kalimantan Utara. Tugas ini merupakan mandat wajib (mandatory) stasiun maritim, yang secara spesifik mencakup pelayanan informasi untuk 3 (tiga) wilayah perairan dan 13 (tiga belas) pelabuhan yang berada di bawah tanggung jawab Stasiun Meteorologi Juwata Tarakan.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melaporkan setiap kejadian cuaca ekstrem yang teramati atau terjadi di sekitar stasiun yang menjadi tanggung jawabnya kepada Badan Meteorologi, Klimatologi, dan Geofisika Pusat. * (Tambahan): Segera menyusun dan mengirimkan laporan respons cepat (quick response) selambat-lambatnya 12 (dua belas) jam setelah kejadian cuaca ekstrem tersebut terverifikasi, yang ditujukan langsung kepada Bagian Peringatan Dini Cuaca Ekstrem BMKG Pusat. * (Tambahan): Menyusun dan mengirimkan laporan analisis kejadian yang lengkap dan mendalam, termasuk data dan dampaknya, selambat-lambatnya 2 (dua) hari setelah kejadian cuaca ekstrem tersebut berlangsung.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Segera melaporkan kejadian letusan gunung berapi dalam format standar Volcanic Activity Report jika terdeteksi di wilayahnya, dan meneruskannya ke Stasiun Meteorologi Kelas I (Soekarno Hatta dan Hasanuddin) untuk disebarkan ke VAAC (Pusat Peringatan Debu Vulkanik) demi keselamatan penerbangan.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan analisis dan penafsiran (interpretasi) terhadap data model prediksi (NWP), gambar dari satelit cuaca, serta citra radar cuaca untuk memahami kondisi atmosfer di wilayah pelayanannya.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Membuat produk prakiraan cuaca yang ditujukan untuk masyarakat umum di wilayah pelayanannya, yang dilakukan secara rutin minimal satu kali setiap hari pada jam 00 UTC (jam 8 pagi WITA).</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Membuat produk prakiraan cuaca yang sangat teknis dan spesifik untuk area bandar udara setempat (Bandara Juwata) yang vital bagi keperluan operasional dan keselamatan penerbangan, serta menyebarkannya sesuai ketentuan yang berlaku.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan pembaruan (updating), publikasi data, dan penyajian seluruh produk informasi prakiraan cuaca secara teratur dalam berbagai bentuk (infografis, tabel, dan narasi teks) agar mudah diakses oleh publik.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melaksanakan penyebarluasan (diseminasi) seluruh produk informasi cuaca secara aktif, proaktif, dan masif agar dapat menjangkau kepentingan publik secara luas di seluruh wilayah Provinsi Kalimantan Utara yang menjadi tanggung jawabnya.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Menyiarkan secara luas informasi peringatan dini cuaca ekstrem kepada publik, terutama melalui media massa dan instansi yang terkait langsung dengan penanggulangan bencana (seperti BPBD).</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melaporkan setiap kejadian cuaca ekstrem yang terpantau di seluruh wilayah pelayanannya (tidak hanya sekitar stasiun) secara resmi ke Badan Meteorologi, Klimatologi, dan Geofisika Pusat.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Membuat, menyebarkan, dan mendokumentasikan laporan rinci setiap kali terjadi cuaca ekstrem, termasuk mencatat dampak yang ditimbulkannya terhadap keselamatan jiwa dan kerugian materi yang terjadi di wilayah pelayanannya.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melaksanakan penyediaan dan penyaluran informasi cuaca krusial untuk pilot dan maskapai, seperti info cuaca untuk pendaratan/lepas landas, peringatan cuaca signifikan di bandara (SIGMET), dan info cuaca di sepanjang rute penerbangan sesuai kebutuhan operasi.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melaksanakan penyediaan dan penyaluran informasi cuaca khusus untuk area pelabuhan, informasi untuk nelayan (pelayaran rakyat), dan buletin cuaca rutin untuk kapal-kapal pelayaran niaga.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Memberikan pelayanan jasa informasi cuaca yang bersifat khusus (custom) berdasarkan permintaan spesifik dari pengguna jasa (misal: perusahaan konstruksi) atau berdasarkan nota kesepahaman (MoU) dengan instansi lain.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Memberikan pelayanan jasa informasi cuaca yang bersifat khusus (custom) berdasarkan permintaan spesifik dari pengguna jasa (misal: perusahaan konstruksi) atau berdasarkan nota kesepahaman (MoU) dengan instansi lain.</span>
                                    </li>
                                </ul>
                            </div>
                        `
                    };
                    break;

                case 'Observer':
                    sopData = {
                        title: 'SOP OBSERVER',
                        icon: 'fa-clipboard-check',
                        color: 'blue',
                        content: `
                            <h4 class="font-semibold text-gray-800 mb-4 text-lg border-b pb-2">Prosedur Inspeksi Peralatan:</h4>
                            <div>
                                <h5 class="font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-tools text-blue-500 mr-2"></i>
                                    Tugas Pokok
                                </h5>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan pencatatan data cuaca di permukaan bumi—seperti suhu, kelembapan, kecepatan angin, dan tekanan udara—secara konsisten dan tanpa henti, dengan interval pengamatan setiap satu jam penuh, selama 24 jam sehari, setiap harinya</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan pengamatan kondisi cuaca di lapisan atmosfer atas (udara atas) dengan cara menerbangkan balon cuaca yang membawa instrumen 'radiosonde', yang dilakukan secara rutin dua kali sehari pada jam standar internasional, yaitu 00 dan 12 UTC (setara jam 8 pagi dan 8 malam WITA).</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan pengamatan tambahan untuk udara atas, khusus untuk mengukur arah dan kecepatan angin, dengan menggunakan 'pilot balon' (pibal) yang diterbangkan pada jam 00, 12, dan 18 UTC, atau menyesuaikan jadwal lain (seperti jam 06 dan 18 UTC) sesuai kebutuhan operasional jaringan.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Menerjemahkan hasil data pengamatan cuaca permukaan yang diperoleh setiap jam ke dalam format kode standar internasional (disebut 'SYNOP') agar bisa dipahami dan dipertukarkan dengan stasiun cuaca lain di seluruh dunia.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Menerjemahkan hasil data pengamatan dari balon radiosonde dan pilot balon (data udara atas) ke dalam format kode standar (disebut 'TEMP' atau 'PILOT') sesuai dengan jadwal dan jam yang telah ditentukan.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan pengamatan cuaca secara spesifik dan non-rutin jika diperlukan oleh jaringan, terutama dengan mengoperasikan dan memantau peralatan canggih seperti radar cuaca (untuk mendeteksi hujan) dan alat penerima gambar dari satelit cuaca.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan pengamatan cuaca permukaan secara khusus untuk mendukung keselamatan penerbangan, dengan menggunakan alat-alat yang terpasang di taman alat dan di sekitar landasan pacu, lalu melaporkan hasilnya dalam format kode penerbangan standar (METAR, SPECI, MET REPORT).
o	Menggunakan peralatan konvensional dan/atau otomatis yang berada di taman alat untuk pengamat meteorologi permukaan (Synop).
o	Melakukan pengamatan di pelayanan meteo penerbangan (METAR, SPECI, MET REPORT, SPECIAL).
</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan pengamatan cuaca permukaan yang difokuskan untuk mendukung keselamatan pelayaran dan aktivitas maritim, dengan menggunakan peralatan yang ada di taman alat serta alat ukur khusus yang ditempatkan di area kolam pelabuhan atau perairan pantai.
o	Menggunakan peralatan konvensional dan/atau otomatis yang tersedia di taman alat & di stasiun di pelabuhan (perairan pantai).
</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan serangkaian pengamatan meteorologi dasar yang mencakup, paling tidak, pengukuran intensitas radiasi matahari, suhu dan tekanan udara, arah dan kecepatan angin, tingkat kelembapan udara, pengamatan kondisi awan, pengukuran jarak pandang (visibility), pencatatan curah hujan, serta pengukuran laju penguapan air.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan serangkaian pengamatan komprehensif yang diwajibkan untuk stasiun maritim, yang mencakup semua unsur pada poin 9 (radiasi, suhu, tekanan, angin, dll.) ditambah dengan pengamatan khusus kelautan, yaitu tinggi gelombang laut, suhu di permukaan air laut, serta pencatatan pasang surut air laut.</span>
                                    </li>
                                </ul>
                            </div>
                        `
                    };
                    break;

                case 'Teknisi':
                    sopData = {
                        title: 'SOP TEKNISI',
                        icon: 'fa-chart-bar',
                        color: 'purple',
                        content: `
                            <h4 class="font-semibold text-gray-800 mb-4 text-lg border-b pb-2">Prosedur Pelaporan:</h4>
                            <div>
                                <h5 class="font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-file-alt text-purple-500 mr-2"></i>
                                    Tugas Pokok
                                </h5>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Menyusun dan menetapkan jadwal untuk semua kegiatan pemeliharaan (maintenance) rutin secara berkala agar peralatan tetap berfungsi optimal.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melaksanakan kegiatan pemeliharaan rutin sesuai jadwal yang telah dibuat, baik untuk peralatan yang ada di stasiun Juwata Tarakan maupun di stasiun-stasiun lain yang berada di bawah koordinasinya.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melaksanakan tindakan perbaikan (reparasi) jika terjadi kerusakan pada peralatan meteorologi, baik yang ada di stasiunnya maupun di stasiun lain yang menjadi tanggung jawabnya.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melaksanakan pemeliharaan dan juga perbaikan terhadap berbagai fasilitas pendukung operasional stasiun, seperti jaringan listrik, generator, atau pendingin ruangan, di stasiunnya dan stasiun lain yang dikoordinasikannya.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melaksanakan pemeliharaan dan juga perbaikan terhadap berbagai fasilitas pendukung operasional stasiun, seperti jaringan listrik, generator, atau pendingin ruangan, di stasiunnya dan stasiun lain yang dikoordinasikannya.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan pemantauan (monitoring) kondisi seluruh peralatan secara aktif dan melaporkan hasil dari pemantauan tersebut secara berjenjang, untuk memastikan semua alat berfungsi normal.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Segera melaporkan setiap kerusakan peralatan yang terjadi, serta melaporkan hasil setelah perbaikan selesai dilakukan, kepada atasan secara berjenjang.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Memberikan laporan resmi secara berjenjang apabila ada peralatan yang terpaksa dihentikan sementara atau permanen pengoperasiannya (misalnya karena rusak berat atau dalam perbaikan).</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan pencatatan dan pengarsipan seluruh sejarah atau riwayat dari setiap peralatan (mulai dari pemasangan, pemeliharaan, hingga perbaikan) dengan rapi di stasiunnya dan stasiun lain di bawahnya.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Melakukan pencatatan dan pelaporan secara berjenjang setiap kali ada perubahan status aset peralatan, misalnya penambahan alat baru, pemindahan lokasi, atau penghapusan alat yang sudah tua/rusak.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Mengajukan usulan kepada pihak berwenang secara berjenjang untuk dilakukannya kalibrasi (peneraan ulang) pada peralatan agar akurasi pengukurannya tetap terjaga sesuai standar.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span>Bertanggung jawab untuk selalu menjaga kebersihan, keamanan, dan kesesuaian kondisi lingkungan (seperti suhu dan kelembapan ruangan) tempat peralatan meteorologi dioperasikan.</span>
                                    </li>
                                </ul>
                            </div>
                        `
                    };
                    break;
            }

            // Set title dengan icon dan warna yang sesuai
            title.innerHTML = `
                <i class="fas ${sopData.icon} mr-2 text-${sopData.color}-500"></i>
                ${sopData.title}
            `;

            // Set content
            content.innerHTML = sopData.content;

            // Show modal
            modal.classList.remove('hidden');
        }

        function closeSopModal() {
            document.getElementById('sopModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('sopModal');
            if (event.target === modal) {
                closeSopModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSopModal();
            }
        });
    </script>
@endsection
