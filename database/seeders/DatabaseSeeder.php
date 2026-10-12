<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Admin (Pastikan email admin sesuai dengan akun yang kamu pakai login)
        User::updateOrCreate(
            ['email' => 'admindelisha@gmail.com'],
            [
                'name' => 'Admin Dapur Mama Delisha',
                'password' => Hash::make('admindelisha_04'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Dapur Mama Delisha Central, Surakarta',
            ]
        );

        // 2. Daftar Lengkap Customer Asli
        $customersData = [
            ['name' => 'NAF\'AN RASYIDI HARTONO', 'email' => 'nafan@customer.com', 'phone' => '081234567801', 'address' => 'Jl. Slamet Riyadi No. 10, Surakarta'],
            ['name' => 'RYANDRA SURYA NANDIKA', 'email' => 'ryandra@customer.com', 'phone' => '081234567802', 'address' => 'Jl. Yos Sudarso No. 15, Surakarta'],
            ['name' => 'MUHAMMAD ILHAM ARBANI', 'email' => 'muhammad.ilham@customer.com', 'phone' => '081234567803', 'address' => 'Jl. Veteran No. 22, Surakarta'],
            ['name' => 'RIZKA KURNIA AHMAD', 'email' => 'rizka@customer.com', 'phone' => '081234567804', 'address' => 'Jl. Gajah Mada No. 45, Surakarta'],
            ['name' => 'WILDAN ADJI PRASETYO', 'email' => 'wildan@customer.com', 'phone' => '081234567805', 'address' => 'Jl. Gonilan Raya No. 3, Kartasura'],
            ['name' => 'MUHAMMAD RAFI MALIKI', 'email' => 'muhammad.rafi@customer.com', 'phone' => '081234567806', 'address' => 'Jl. A. Yani No. 88, Surakarta'],
            ['name' => 'APRISCA ANANDA ROSDIANTY', 'email' => 'aprisca@customer.com', 'phone' => '081234567807', 'address' => 'Perum Griya Indah Blok A/5, Sukoharjo'],
            ['name' => 'YUSUF KURNIAWAN', 'email' => 'yusuf@customer.com', 'phone' => '081234567808', 'address' => 'Jl. Adi Sucipto No. 12, Colomadu'],
            ['name' => 'CIKAL OKTAVIANURJANAH QOMARONI', 'email' => 'cikal@customer.com', 'phone' => '081234567809', 'address' => 'Jl. Mojo No. 7, Surakarta'],
            ['name' => 'ANDIKA ILHAM PRADANA', 'email' => 'andika@customer.com', 'phone' => '081234567810', 'address' => 'Jl. Ir. Soekarno No. 25, Solo Baru'],
            ['name' => 'DWININGSIH', 'email' => 'dwiningsih@customer.com', 'phone' => '081234567811', 'address' => 'Jl. Melati No. 14, Surakarta'],
            ['name' => 'NASYWA TABITA RASYID', 'email' => 'nasywa@customer.com', 'phone' => '081234567812', 'address' => 'Jl. Cempaka No. 9, Sukoharjo'],
            ['name' => 'AKBAR RIZKY DIO SAPUTRA', 'email' => 'akbar@customer.com', 'phone' => '081234567813', 'address' => 'Jl. Mawar No. 11, Surakarta'],
            ['name' => 'KHAIRUL IKHSAN', 'email' => 'khairul@customer.com', 'phone' => '081234567814', 'address' => 'Jl. Kenanga No. 4, Kartasura'],
            ['name' => 'FIRMAN ANDRIYANTO ARIEF', 'email' => 'firman@customer.com', 'phone' => '081234567815', 'address' => 'Jl. Diponegoro No. 33, Surakarta'],
            ['name' => 'RETNO PUTRI RAHMADANI', 'email' => 'retno@customer.com', 'phone' => '081234567816', 'address' => 'Jl. Pattimura No. 21, Surakarta'],
            ['name' => 'RAVA EKA CAHYONO', 'email' => 'rava@customer.com', 'phone' => '081234567817', 'address' => 'Jl. Imam Bonjol No. 8, Sukoharjo'],
            ['name' => 'BINTANG PUTRA ARYATAMA', 'email' => 'bintang@customer.com', 'phone' => '081234567818', 'address' => 'Jl. Monginsidi No. 50, Surakarta'],
            ['name' => 'NIKODEMUS CHRISTIANO DAVID', 'email' => 'nikodemus@customer.com', 'phone' => '081234567819', 'address' => 'Jl. Dr. Setiabudi No. 19, Surakarta'],
            ['name' => 'JONATHAN ALDO SETIAWAN', 'email' => 'jonathan@customer.com', 'phone' => '081234567820', 'address' => 'Jl. Sutan Sjahrir No. 6, Surakarta'],
            ['name' => 'MUHAMMAD RAFI SALMAN', 'email' => 'muhammad.rafi.s@customer.com', 'phone' => '081234567821', 'address' => 'Jl. Ronggowarsito No. 12, Surakarta'],
            ['name' => 'DAFFA ISLAM FATAHILLAH', 'email' => 'daffa@customer.com', 'phone' => '081234567822', 'address' => 'Jl. Kapten Mulyadi No. 28, Surakarta'],
            ['name' => 'RESTU NARENDRASTOMO', 'email' => 'restu@customer.com', 'phone' => '081234567823', 'address' => 'Jl. Urip Sumoharjo No. 40, Surakarta'],
            ['name' => 'ADHIKA TYO FERDIANSYAH', 'email' => 'adhika@customer.com', 'phone' => '081234567824', 'address' => 'Jl. Kolonel Sutarto No. 55, Surakarta'],
            ['name' => 'GILANG PAMUNGKAS', 'email' => 'gilang@customer.com', 'phone' => '081234567825', 'address' => 'Jl. Ki Hajar Dewantara No. 3, Surakarta'],
            ['name' => 'MUHAMMAD GALIH ARIFIN', 'email' => 'muhammad.galih@customer.com', 'phone' => '081234567826', 'address' => 'Jl. Kartini No. 14, Sukoharjo'],
            ['name' => 'NOE URIEL KURNIANTO', 'email' => 'noe@customer.com', 'phone' => '081234567827', 'address' => 'Jl. Raden Mas No. 7, Surakarta'],
            ['name' => 'ALVINDO PUJA PRATAMA', 'email' => 'alvindo@customer.com', 'phone' => '081234567828', 'address' => 'Jl. Perintis Kemerdekaan No. 9, Surakarta'],
            ['name' => 'ARDYA ZAHIRA ARDHANA', 'email' => 'ardya@customer.com', 'phone' => '081234567829', 'address' => 'Jl. Parang Kesit No. 11, Surakarta'],
            ['name' => 'MUH RIZKY ANGGARA', 'email' => 'muh.rizky@customer.com', 'phone' => '081234567830', 'address' => 'Jl. Kiai Mojo No. 20, Surakarta'],
            ['name' => 'IKHWALIL BAHARI SYAM', 'email' => 'ikhwalil@customer.com', 'phone' => '081234567831', 'address' => 'Jl. Kyai Ageng Gribig No. 5, Surakarta'],
            ['name' => 'MUHAMMAD FERDIHARVAN', 'email' => 'muhammad.ferdiharvan@customer.com', 'phone' => '081234567832', 'address' => 'Jl. Letjen Sutoyo No. 18, Surakarta'],
            ['name' => 'MUH RAFLY FATCHUROHMAN', 'email' => 'muh.rafly@customer.com', 'phone' => '081234567833', 'address' => 'Jl. Mojo Indah No. 3, Surakarta'],
            ['name' => 'HONGGO NEGORO SENO SRI', 'email' => 'honggo@customer.com', 'phone' => '081234567834', 'address' => 'Jl. Cik Ditiro No. 12, Surakarta'],
            ['name' => 'R. ROMIZ AISY', 'email' => 'romiz@customer.com', 'phone' => '081234567835', 'address' => 'Jl. Teuku Umar No. 22, Surakarta'],
            ['name' => 'ILHAM PEBRIANTO EKO SAPUTRA', 'email' => 'ilham.pebrianto@customer.com', 'phone' => '081234567836', 'address' => 'Jl. Hasanuddin No. 44, Surakarta'],
            ['name' => 'LUCKY AMADYA FALSABILA', 'email' => 'lucky@customer.com', 'phone' => '081234567837', 'address' => 'Jl. Punggawan No. 2, Surakarta'],
            ['name' => 'KRESNA BAYU PRASETYO', 'email' => 'kresna@customer.com', 'phone' => '081234567838', 'address' => 'Jl. Gilingan No. 10, Surakarta'],
            ['name' => 'WIRDIAN SILVANI', 'email' => 'wirdian@customer.com', 'phone' => '081234567839', 'address' => 'Jl. Nusukan No. 15, Surakarta'],
            ['name' => 'ILMAYA NI\'MATUL FATMA', 'email' => 'ilmaya@customer.com', 'phone' => '081234567840', 'address' => 'Jl. Banyuanyar No. 8, Surakarta'],
            ['name' => 'OKTA VIONA CAHYANTI', 'email' => 'okta@customer.com', 'phone' => '081234567841', 'address' => 'Jl. Manahan No. 6, Surakarta'],
            ['name' => 'SYAKARA AKBAR', 'email' => 'syakara@customer.com', 'phone' => '081234567842', 'address' => 'Jl. Sumber No. 14, Surakarta'],
            ['name' => 'DYAH PUTRI ANGGRAINI', 'email' => 'dyah@customer.com', 'phone' => '081234567843', 'address' => 'Jl. Kerten No. 9, Surakarta'],
            ['name' => 'ALPIN DANUARTA', 'email' => 'alpin@customer.com', 'phone' => '081234567844', 'address' => 'Jl. Laweyan No. 25, Surakarta'],
            ['name' => 'EZAR RAMADHAN', 'email' => 'ezar@customer.com', 'phone' => '081234567845', 'address' => 'Jl. Serengan No. 31, Surakarta'],
            ['name' => 'DINA EKA FITRIANI', 'email' => 'dina@customer.com', 'phone' => '081234567846', 'address' => 'Jl. Jayengan No. 4, Surakarta'],
            ['name' => 'AZFA YASHIFA RAMADHAN', 'email' => 'azfa@customer.com', 'phone' => '081234567847', 'address' => 'Jl. Kauman No. 12, Surakarta'],
            ['name' => 'RIKO FERDIANSYAH', 'email' => 'riko@customer.com', 'phone' => '081234567848', 'address' => 'Jl. Pasar Kliwon No. 7, Surakarta'],
            ['name' => 'YUSUP AFIFURROHMAN', 'email' => 'yusup@customer.com', 'phone' => '081234567849', 'address' => 'Jl. Semanggi No. 20, Surakarta'],
            ['name' => 'SCHATZI AURELIA REYADHI', 'email' => 'schatzi@customer.com', 'phone' => '081234567850', 'address' => 'Jl. Jebres No. 16, Surakarta'],
            ['name' => 'MUHAMMAD GILANG FEBRIAN', 'email' => 'muhammad.gilang@customer.com', 'phone' => '081234567851', 'address' => 'Jl. Pucangsawit No. 5, Surakarta'],
            ['name' => 'DZAKI SATRIATAMA', 'email' => 'dzaki@customer.com', 'phone' => '081234567852', 'address' => 'Jl. Gulon No. 8, Surakarta'],
            ['name' => 'ERWIN ARDIAN NUGRAHA', 'email' => 'erwin@customer.com', 'phone' => '081234567853', 'address' => 'Jl. Jagalan No. 11, Surakarta'],
            ['name' => 'FACHRIZA ULFAH', 'email' => 'fachriza@customer.com', 'phone' => '081234567854', 'address' => 'Jl. Purwosari No. 3, Surakarta'],
            ['name' => 'FARHAN NAUFAL MUBAROK', 'email' => 'farhan.naufal@customer.com', 'phone' => '081234567855', 'address' => 'Jl. Penumping No. 17, Surakarta'],
            ['name' => 'NATASHA ZULATIFA', 'email' => 'natasha@customer.com', 'phone' => '081234567856', 'address' => 'Jl. Sriwedari No. 22, Surakarta'],
            ['name' => 'REGITA INDAH CAHYANI', 'email' => 'regita@customer.com', 'phone' => '081234567857', 'address' => 'Jl. Panularan No. 4, Surakarta'],
            ['name' => 'KHAYRURAYA ABRAR JULVIANSYAH', 'email' => 'khayruraya@customer.com', 'phone' => '081234567858', 'address' => 'Jl. Sondakan No. 9, Surakarta'],
            ['name' => 'FARHAN NUR ALAM', 'email' => 'farhan.nur@customer.com', 'phone' => '081234567859', 'address' => 'Jl. Karangasem No. 14, Surakarta'],
            ['name' => 'ROMA ARRIZAL OMAR SYAH', 'email' => 'roma@customer.com', 'phone' => '081234567860', 'address' => 'Jl. Pajang No. 6, Surakarta'],
            ['name' => 'SIGIT DWIANTO', 'email' => 'sigit@customer.com', 'phone' => '081234567861', 'address' => 'Jl. Makamhaji No. 12, Kartasura'],
            ['name' => 'GALIH MUHAMMAD', 'email' => 'galih@customer.com', 'phone' => '081234567862', 'address' => 'Jl. Pabelan No. 18, Kartasura'],
            ['name' => 'BAYU AJI UTOMO', 'email' => 'bayu.aji@customer.com', 'phone' => '081234567863', 'address' => 'Jl. Ngasem No. 20, Colomadu'],
            ['name' => 'SOFFIN THORIQ ARFIAN', 'email' => 'soffin@customer.com', 'phone' => '081234567864', 'address' => 'Jl. Blulukan No. 5, Colomadu'],
            ['name' => 'PRATAMA ANDIKA SETYAWAN', 'email' => 'pratama@customer.com', 'phone' => '081234567865', 'address' => 'Jl. Paulan No. 7, Colomadu'],
            ['name' => 'USWATUN KHASANAH', 'email' => 'uswatun@customer.com', 'phone' => '081234567866', 'address' => 'Jl. Baturan No. 15, Colomadu'],
            ['name' => 'MUHAMMAD DIGDOYO RIO ARDIANTO', 'email' => 'muhammad.digdoyo@customer.com', 'phone' => '081234567867', 'address' => 'Jl. Colomadu Raya No. 10, Karanganyar'],
            ['name' => 'AVRESIA DIVA AMYZAHRA', 'email' => 'avresia@customer.com', 'phone' => '081234567868', 'address' => 'Jl. Gawanan No. 4, Colomadu'],
            ['name' => 'MUHAMMAD FIRDAUS ALFARIZI', 'email' => 'muhammad.firdaus@customer.com', 'phone' => '081234567869', 'address' => 'Jl. Klodran No. 9, Colomadu'],
            ['name' => 'HUSNAINI AINUN NI\'MAH', 'email' => 'husnaini@customer.com', 'phone' => '081234567870', 'address' => 'Jl. Fajar Indah No. 22, Surakarta'],
            ['name' => 'NAUFAL HANAN JATI ASMARA', 'email' => 'naufal.hanan@customer.com', 'phone' => '081234567871', 'address' => 'Jl. Kerten Indah No. 3, Surakarta'],
            ['name' => 'IRFAN MAULANA', 'email' => 'irfan@customer.com', 'phone' => '081234567872', 'address' => 'Jl. Karangasem Baru No. 11, Surakarta'],
            ['name' => 'ARYA ADI RESTU PUTRA PRATAMA', 'email' => 'arya.adi@customer.com', 'phone' => '081234567873', 'address' => 'Jl. Nilam No. 5, Surakarta'],
            ['name' => 'FAYI AGNIA LIMARA', 'email' => 'fayi@customer.com', 'phone' => '081234567874', 'address' => 'Jl. Berlian No. 8, Surakarta'],
            ['name' => 'RAHMAT SYAIFULLOH', 'email' => 'rahmat@customer.com', 'phone' => '081234567875', 'address' => 'Jl. Intan No. 12, Surakarta'],
            ['name' => 'RHYCO FEBRIAN NALENDRA PUTRA', 'email' => 'rhyco@customer.com', 'phone' => '081234567876', 'address' => 'Jl. Topaz No. 4, Surakarta'],
            ['name' => 'FATKHUR RAHMAN ADI NUGROHO', 'email' => 'fatkhur@customer.com', 'phone' => '081234567877', 'address' => 'Jl. Safir No. 7, Surakarta'],
            ['name' => 'PENIEL MANURUNG', 'email' => 'peniel@customer.com', 'phone' => '081234567878', 'address' => 'Jl. Amarta No. 10, Surakarta'],
            ['name' => 'RIZKY PARA DIKDAYA', 'email' => 'rizky.para@customer.com', 'phone' => '081234567879', 'address' => 'Jl. Puntadewa No. 3, Surakarta'],
            ['name' => 'MUZAKI SYIFAUZ ZAIN ARRIFKI', 'email' => 'muzaki@customer.com', 'phone' => '081234567880', 'address' => 'Jl. Werkudoro No. 15, Surakarta'],
            ['name' => 'BAYU RIZKY UTOMO', 'email' => 'bayu.rizky@customer.com', 'phone' => '081234567881', 'address' => 'Jl. Janaka No. 6, Surakarta'],
            ['name' => 'HAVID DANGGO PAMUNGKAS', 'email' => 'havid@customer.com', 'phone' => '081234567882', 'address' => 'Jl. Srikandi No. 9, Surakarta'],
            ['name' => 'ARIG KUSUMA JATI', 'email' => 'arig@customer.com', 'phone' => '081234567883', 'address' => 'Jl. Gatot Subroto No. 20, Surakarta'],
            ['name' => 'MUHAMMAD FAHRI', 'email' => 'muhammad.fahri@customer.com', 'phone' => '081234567884', 'address' => 'Jl. Sutan Syahrir No. 14, Surakarta'],
            ['name' => 'FITO PATRIA', 'email' => 'fito@customer.com', 'phone' => '081234567885', 'address' => 'Jl. Veteran No. 88, Surakarta'],
            ['name' => 'RAFLESIA MUKHLIS JULIYANTO', 'email' => 'raflesia@customer.com', 'phone' => '081234567886', 'address' => 'Jl. Perintis Kemerdekaan No. 30, Surakarta'],
            ['name' => 'MUHAMMAD BINTANG PRASETYO', 'email' => 'muhammad.bintang@customer.com', 'phone' => '081234567887', 'address' => 'Jl. Adi Sucipto No. 45, Surakarta'],
            ['name' => 'MUHAMMAD RAUUF WICAKSONO', 'email' => 'muhammad.rauuf@customer.com', 'phone' => '081234567888', 'address' => 'Jl. Slamet Riyadi No. 100, Surakarta'],
            ['name' => 'NICE PUTRI MENTARI', 'email' => 'nice@customer.com', 'phone' => '081234567889', 'address' => 'Jl. Yos Sudarso No. 50, Surakarta'],
            ['name' => 'FAUZAN INDRA KUSUMA', 'email' => 'fauzan.indra@customer.com', 'phone' => '081234567890', 'address' => 'Jl. Gajah Mada No. 18, Surakarta'],
            ['name' => 'ARUM SARI SETIA BUDI', 'email' => 'arum.sari@customer.com', 'phone' => '081234567891', 'address' => 'Jl. Dr. Radjiman No. 75, Surakarta'],
            ['name' => 'KIRANA AMARIA KIESYA', 'email' => 'kirana@customer.com', 'phone' => '081234567892', 'address' => 'Jl. Kenanga No. 22, Surakarta'],
            ['name' => 'APRILLIO MALIKA ENDIWAN', 'email' => 'aprillio@customer.com', 'phone' => '081234567893', 'address' => 'Jl. Melati No. 5, Surakarta'],
            ['name' => 'MUHAMMAD AL-FARISI', 'email' => 'muhammad.alfarisi@customer.com', 'phone' => '081234567894', 'address' => 'Jl. Mawar No. 33, Surakarta'],
            ['name' => 'ANITA DEWI KUSUMA', 'email' => 'anita@customer.com', 'phone' => '081234567895', 'address' => 'Jl. Cempaka No. 12, Surakarta'],
            ['name' => 'YOKA HOBI AMALIAWATI', 'email' => 'yoka@customer.com', 'phone' => '081234567896', 'address' => 'Jl. Diponegoro No. 14, Surakarta'],
            ['name' => 'BIMA ADITYA', 'email' => 'bima@customer.com', 'phone' => '081234567897', 'address' => 'Jl. Pattimura No. 9, Surakarta'],
            ['name' => 'FAVIAN MAHSA WAHENDRA', 'email' => 'favian@customer.com', 'phone' => '081234567898', 'address' => 'Jl. Imam Bonjol No. 16, Surakarta'],
            ['name' => 'YOSAFAT GERRIMANDARIL', 'email' => 'yosafat@customer.com', 'phone' => '081234567899', 'address' => 'Jl. Monginsidi No. 21, Surakarta'],
            ['name' => 'DAVINA YELA GAINTA', 'email' => 'davina@customer.com', 'phone' => '081234567900', 'address' => 'Jl. Dr. Setiabudi No. 30, Surakarta'],
            ['name' => 'ARAMI RIZKI GUNAWAN', 'email' => 'arami@customer.com', 'phone' => '081234567901', 'address' => 'Jl. Ronggowarsito No. 40, Surakarta'],
            ['name' => 'FAJAR AS SHIDIK', 'email' => 'fajar@customer.com', 'phone' => '081234567902', 'address' => 'Jl. Kapten Mulyadi No. 50, Surakarta'],
            ['name' => 'ARADEA PINKAN KARTININGTYAS', 'email' => 'aradea@customer.com', 'phone' => '081234567903', 'address' => 'Jl. Urip Sumoharjo No. 15, Surakarta'],
            ['name' => 'WISNU RASYIDIN AZHARI', 'email' => 'wisnu@customer.com', 'phone' => '081234567904', 'address' => 'Jl. Kolonel Sutarto No. 22, Surakarta'],
            ['name' => 'RISKI SUKMA JAYA', 'email' => 'riski@customer.com', 'phone' => '081234567905', 'address' => 'Jl. Ki Hajar Dewantara No. 12, Surakarta'],
            ['name' => 'SHAVIRA AZRA', 'email' => 'shavira@customer.com', 'phone' => '081234567906', 'address' => 'Jl. Kartini No. 5, Sukoharjo'],
            ['name' => 'NANDA RAHMA ANGGYTA', 'email' => 'nanda@customer.com', 'phone' => '081234567907', 'address' => 'Jl. Raden Mas No. 19, Surakarta'],
            ['name' => 'ADITYA ARIEF DARMAWAN', 'email' => 'aditya@customer.com', 'phone' => '081234567908', 'address' => 'Jl. Perintis Kemerdekaan No. 44, Surakarta'],
            ['name' => 'ARUM VILIA PUTRI', 'email' => 'arum.vilia@customer.com', 'phone' => '081234567909', 'address' => 'Jl. Parang Kesit No. 3, Surakarta'],
            ['name' => 'DEVYTHA NUR ALFI', 'email' => 'devytha@customer.com', 'phone' => '081234567910', 'address' => 'Jl. Kiai Mojo No. 11, Surakarta'],
            ['name' => 'ILHAM ABIANSYAH', 'email' => 'ilham.abiansyah@customer.com', 'phone' => '081234567911', 'address' => 'Jl. Kyai Ageng Gribig No. 18, Surakarta'],
            ['name' => 'YUDHISTIRA EKA PRASETYA', 'email' => 'yudhistira@customer.com', 'phone' => '081234567912', 'address' => 'Jl. Letjen Sutoyo No. 9, Surakarta'],
            ['name' => 'NAOMI RUTH MAHARANI', 'email' => 'naomi@customer.com', 'phone' => '081234567913', 'address' => 'Jl. Mojo Indah No. 12, Surakarta'],
            ['name' => 'INDI NAJWA ALIFIA', 'email' => 'indi@customer.com', 'phone' => '081234567914', 'address' => 'Jl. Cik Ditiro No. 4, Surakarta'],
            ['name' => 'MUHAMMAD DAFFA RAMDANI', 'email' => 'muhammad.daffa@customer.com', 'phone' => '081234567915', 'address' => 'Jl. Teuku Umar No. 11, Surakarta'],
            ['name' => 'KEVIN RIZKI FAUZI', 'email' => 'kevin@customer.com', 'phone' => '081234567916', 'address' => 'Jl. Hasanuddin No. 23, Surakarta'],
            ['name' => 'PUTRI ALFIYA', 'email' => 'putri.alfiya@customer.com', 'phone' => '081234567917', 'address' => 'Jl. Punggawan No. 7, Surakarta'],
            ['name' => 'DANIAR GALANG SETYA PUTRA', 'email' => 'daniar@customer.com', 'phone' => '081234567918', 'address' => 'Jl. Gilingan No. 25, Surakarta'],
            ['name' => 'KHOIRUL MUSTHOFA', 'email' => 'khoirul@customer.com', 'phone' => '081234567919', 'address' => 'Jl. Nusukan No. 30, Surakarta'],
            ['name' => 'HAIKAL AZZIKRA PURWOKO', 'email' => 'haikal@customer.com', 'phone' => '081234567920', 'address' => 'Jl. Banyuanyar No. 14, Surakarta'],
            ['name' => 'ATRIYA NAUFALIKA INDAH ARDIANI', 'email' => 'atriya@customer.com', 'phone' => '081234567921', 'address' => 'Jl. Manahan No. 19, Surakarta'],
            ['name' => 'NIRMALA MAHARANI', 'email' => 'nirmala.maharani@customer.com', 'phone' => '081234567922', 'address' => 'Jl. Gonilan No. 8, Kartasura'],
            ['name' => 'ANDREA RAMADHAN CAHYA ARYANA', 'email' => 'andrea@customer.com', 'phone' => '081234567923', 'address' => 'Jl. Sumber No. 2, Surakarta'],
            ['name' => 'HABIB MUHAMMAD RIDWAN', 'email' => 'habib@customer.com', 'phone' => '081234567924', 'address' => 'Jl. Kerten No. 16, Surakarta'],
            ['name' => 'ROSYID ADI WIDODO', 'email' => 'rosyid@customer.com', 'phone' => '081234567925', 'address' => 'Jl. Laweyan No. 8, Surakarta'],
            ['name' => 'MUHAMMAD YUSUF', 'email' => 'muhammad.yusuf@customer.com', 'phone' => '081234567926', 'address' => 'Jl. Serengan No. 12, Surakarta'],
            ['name' => 'DAVID JONATHAN WIJAYA', 'email' => 'david@customer.com', 'phone' => '081234567927', 'address' => 'Jl. Jayengan No. 19, Surakarta'],
            ['name' => 'DICKY KURNIAWAN', 'email' => 'dicky@customer.com', 'phone' => '081234567928', 'address' => 'Jl. Kauman No. 25, Surakarta'],
            ['name' => 'RANGGA HUTAMA PUTRA', 'email' => 'rangga@customer.com', 'phone' => '081234567929', 'address' => 'Jl. Pasar Kliwon No. 15, Surakarta'],
            ['name' => 'FAUZAN AZIMA', 'email' => 'fauzan.azima@customer.com', 'phone' => '081234567930', 'address' => 'Jl. Semanggi No. 9, Surakarta'],
            ['name' => 'VIONA LENY ANJANI', 'email' => 'viona@customer.com', 'phone' => '081234567931', 'address' => 'Jl. Jebres No. 44, Surakarta'],
            ['name' => 'DANU JAYA MUKTI NUGROHO', 'email' => 'danu@customer.com', 'phone' => '081234567932', 'address' => 'Jl. Pucangsawit No. 12, Surakarta'],
            ['name' => 'NURCAHYO SAPUTRO', 'email' => 'nurcahyo@customer.com', 'phone' => '081234567933', 'address' => 'Jl. Gulon No. 17, Surakarta'],
            ['name' => 'FERDIRA VARERA', 'email' => 'ferdira@customer.com', 'phone' => '081234567934', 'address' => 'Jl. Jagalan No. 29, Surakarta'],
            ['name' => 'FAIZ NADHIRA MUBAROK', 'email' => 'faiz@customer.com', 'phone' => '081234567935', 'address' => 'Jl. Purwosari No. 14, Surakarta'],
            ['name' => 'ISNAND FARIZKY PRABOWO', 'email' => 'isnand@customer.com', 'phone' => '081234567936', 'address' => 'Jl. Penumping No. 5, Surakarta'],
            ['name' => 'HANIFAH MUTHI', 'email' => 'hanifah@customer.com', 'phone' => '081234567937', 'address' => 'Jl. Sriwedari No. 8, Surakarta'],
            ['name' => 'VINN TENZO ABDILLAH', 'email' => 'vinn@customer.com', 'phone' => '081234567938', 'address' => 'Jl. Panularan No. 21, Surakarta'],
            ['name' => 'SHANNY NOVALINA TURNIP', 'email' => 'shanny@customer.com', 'phone' => '081234567939', 'address' => 'Jl. Sondakan No. 13, Surakarta'],
            ['name' => 'YASIR GALUH ADI SAPUTRO', 'email' => 'yasir@customer.com', 'phone' => '081234567940', 'address' => 'Jl. Karangasem No. 20, Surakarta'],
            ['name' => 'AREGA ETA PAMUNGKAS', 'email' => 'arega@customer.com', 'phone' => '081234567941', 'address' => 'Jl. Pajang No. 15, Surakarta'],
            ['name' => 'ARYA SAULTANAYA', 'email' => 'arya.saultanaya@customer.com', 'phone' => '081234567942', 'address' => 'Jl. Makamhaji No. 25, Kartasura'],
            ['name' => 'MUHAMMAD \'IZZUL ISLAM', 'email' => 'muhammad.izzul@customer.com', 'phone' => '081234567943', 'address' => 'Jl. Pabelan No. 9, Kartasura'],
            ['name' => 'TEGAR RISKY NUGRAHANTO', 'email' => 'tegar@customer.com', 'phone' => '081234567944', 'address' => 'Jl. Ngasem No. 11, Colomadu'],
            ['name' => 'BAGUS ADITYA SAPUTRA', 'email' => 'bagus@customer.com', 'phone' => '081234567945', 'address' => 'Jl. Blulukan No. 18, Colomadu'],
            ['name' => 'ULFAH NUR UZLIFAH', 'email' => 'ulfah@customer.com', 'phone' => '081234567946', 'address' => 'Jl. Paulan No. 14, Colomadu'],
            ['name' => 'MUHAMMAD DZAKIRIN', 'email' => 'muhammad.dzakirin@customer.com', 'phone' => '081234567947', 'address' => 'Jl. Baturan No. 3, Colomadu'],
            ['name' => 'OKTAFIAN DYAH PANGESTI', 'email' => 'oktafian@customer.com', 'phone' => '081234567948', 'address' => 'Jl. Colomadu Indah No. 5, Karanganyar'],
            ['name' => 'LILING DARU SURYAWATI', 'email' => 'liling@customer.com', 'phone' => '081234567949', 'address' => 'Jl. Gawanan No. 12, Colomadu'],
            ['name' => 'SYAMSUL DAHLAN', 'email' => 'syamsul@customer.com', 'phone' => '081234567950', 'address' => 'Jl. Klodran No. 20, Colomadu'],
            ['name' => 'M RENO AFRIDO ARWINTO', 'email' => 'm.reno@customer.com', 'phone' => '081234567951', 'address' => 'Jl. Fajar Indah No. 4, Surakarta'],
            ['name' => 'IRHASH ZIM ZAM DITO PRAYOGA', 'email' => 'irhash@customer.com', 'phone' => '081234567952', 'address' => 'Jl. Kerten Indah No. 15, Surakarta'],
            ['name' => 'DHIMAS PUTRA NUR HIDAYAT', 'email' => 'dhimas@customer.com', 'phone' => '081234567953', 'address' => 'Jl. Karangasem Baru No. 22, Surakarta'],
            ['name' => 'BAYU ADE PRASETIYA', 'email' => 'bayu.ade@customer.com', 'phone' => '081234567954', 'address' => 'Jl. Nilam No. 19, Surakarta'],
            ['name' => 'ASWINDO ARTHA SYAHPUTRA', 'email' => 'aswindo@customer.com', 'phone' => '081234567955', 'address' => 'Jl. Berlian No. 3, Surakarta'],
            ['name' => 'MUHAMMAD LEXA', 'email' => 'muhammad.lexa@customer.com', 'phone' => '081234567956', 'address' => 'Jl. Intan No. 25, Surakarta'],
            ['name' => 'NAUFAL FALAH', 'email' => 'naufal.falah@customer.com', 'phone' => '081234567957', 'address' => 'Jl. Topaz No. 11, Surakarta'],
            ['name' => 'KHOIRUNNISA', 'email' => 'khoirunnisa@customer.com', 'phone' => '081234567958', 'address' => 'Jl. Safir No. 18, Surakarta'],
            ['name' => 'SYAHRUL IRFAN HIDAYAT', 'email' => 'syahrul@customer.com', 'phone' => '081234567959', 'address' => 'Jl. Amarta No. 30, Surakarta'],
            ['name' => 'ANGGI SUSANTI', 'email' => 'anggi@customer.com', 'phone' => '081234567960', 'address' => 'Jl. Puntadewa No. 14, Surakarta'],
            ['name' => 'IRSYAD BADRUDDIN', 'email' => 'irsyad@customer.com', 'phone' => '081234567961', 'address' => 'Jl. Werkudoro No. 2, Surakarta'],
            ['name' => 'SATRIA TABAH RAHARJA', 'email' => 'satria@customer.com', 'phone' => '081234567962', 'address' => 'Jl. Janaka No. 17, Surakarta'],
            ['name' => 'AHMAD FIRNANDO FASHIKHUL FALAH', 'email' => 'ahmad.firnando@customer.com', 'phone' => '081234567963', 'address' => 'Jl. Srikandi No. 21, Surakarta'],
            ['name' => 'NANDA PUTRA PAMUNGKAS', 'email' => 'nanda.putra@customer.com', 'phone' => '081234567964', 'address' => 'Jl. Gatot Subroto No. 5, Surakarta'],
            ['name' => 'LINTANG BAWINDRA SURYA A.Md', 'email' => 'lintang@customer.com', 'phone' => '081234567965', 'address' => 'Jl. Sutan Syahrir No. 30, Surakarta'],
            ['name' => 'FATHONI SHOLEH PRASETYA', 'email' => 'fathoni@customer.com', 'phone' => '081234567966', 'address' => 'Jl. Veteran No. 12, Surakarta'],
            ['name' => 'MAULANA YAHYA MAJID', 'email' => 'maulana@customer.com', 'phone' => '081234567967', 'address' => 'Jl. Perintis Kemerdekaan No. 8, Surakarta'],
            ['name' => 'NUARI DWI UTAMA', 'email' => 'nuari@customer.com', 'phone' => '081234567968', 'address' => 'Jl. Adi Sucipto No. 11, Surakarta'],
        ];

        foreach ($customersData as $c) {
            User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone' => $c['phone'],
                    'address' => $c['address'],
                ]
            );
        }

        // 3. Seed Categories
        $catNasiBox = Category::updateOrCreate(['slug' => 'nasi-box'], [
            'name' => 'Nasi Box', 
            'description' => 'Paket praktis dan higienis, sangat cocok untuk konsumsi rapat, seminar, acara kantor, maupun syukuran harian.'
        ]);
        
        $catSnackBasah = Category::updateOrCreate(['slug' => 'snack-basah'], [
            'name' => 'Snack Basah', 
            'description' => 'Aneka jajanan pasar tradisional dan kue basah pilihan yang lezat.'
        ]);
        
        $catSnackKering = Category::updateOrCreate(['slug' => 'snack-kering'], [
            'name' => 'Nasi Box', 
            'description' => 'Berbagai pilihan kue kering dan camilan gurih berkualitas tinggi.'
        ]);

        $catMinuman = Category::updateOrCreate(['slug' => 'minuman'], [
            'name' => 'Minuman', 
            'description' => 'Aneka pilihan minuman segar penghilang dahaga.'
        ]);

        Category::whereNotIn('slug', ['nasi-box', 'snack-basah', 'snack-kering', 'minuman'])->delete();

        // 4. Seed Menus (Tanpa Kue Kacang, Kue Lumpur, dan Putri Salju)
        Menu::updateOrCreate(['slug' => 'ayam-goreng'], [
            'category_id' => $catNasiBox->id,
            'name' => 'Ayam Goreng',
            'description' => 'Nasi dengan ayam goreng paha atau tepong yang gurih dan renyah, dilengkapi lalapan, sambal, tahu atau tempe, serta sayur.',
            'price' => 25000.00,
            'is_favorite' => true,
        ]);

        Menu::updateOrCreate(['slug' => 'ayam-bakar'], [
            'category_id' => $catNasiBox->id,
            'name' => 'Ayam Bakar',
            'description' => 'Nasi dengan ayam bakar berbumbu manis dan gurih, dilengkapi lalapan, sambal, tahu atau tempe, serta sayur.',
            'price' => 26000.00,
            'is_favorite' => true,
        ]);

        Menu::updateOrCreate(['slug' => 'ayam-bakar-gandeng'], [
            'category_id' => $catNasiBox->id,
            'name' => 'Ayam Bakar Gandeng',
            'description' => 'Nasi dengan ayam bakar tepung gandeng yang gurih dan berbumbu, disajikan bersama tahu atau tempe, lalapan, dan sambal.',
            'price' => 28000.00,
            'is_favorite' => false,
        ]);

        Menu::updateOrCreate(['slug' => 'ayam-goreng-gandeng'], [
            'category_id' => $catNasiBox->id,
            'name' => 'Ayam Goreng Gandeng',
            'description' => 'Nasi dengan ayam goreng tepung gandeng yang renyah, dilengkapi tahu atau tempe, lalapan, dan sambal.',
            'price' => 27000.00,
            'is_favorite' => false,
        ]);

        Menu::updateOrCreate(['slug' => 'ayam-gongso'], [
            'category_id' => $catNasiBox->id,
            'name' => 'Ayam Gongso',
            'description' => 'Nasi dengan ayam gongso berbumbu khas yang gurih dan kaya rasa, dilengkapi tahu atau tempe, lalapan, dan sambal.',
            'price' => 27000.00,
            'is_favorite' => true,
        ]);

        Menu::updateOrCreate(['slug' => 'ayam-kentucky'], [
            'category_id' => $catNasiBox->id,
            'name' => 'Ayam Kentucky',
            'description' => 'Nasi dengan ayam kentaki berbalut tepung renyah, disajikan bersama saus atau sambal pilihan.',
            'price' => 24000.00,
            'is_favorite' => false,
        ]);

        $minumanList = [
            ['name' => 'Es Teh', 'price' => 3000, 'desc' => 'Teh manis segar yang disajikan dingin dengan es batu, cocok sebagai pendamping berbagai hidangan.'],
            ['name' => 'Teh Hangat', 'price' => 3000, 'desc' => 'Teh hangat dengan rasa manis yang pas dan aroma teh yang menenangkan.'],
            ['name' => 'Es Jeruk', 'price' => 5000, 'desc' => 'Minuman jeruk segar dengan rasa manis dan sedikit asam yang menyegarkan.'],
            ['name' => 'Jeruk Hangat', 'price' => 5000, 'desc' => 'Minuman jeruk hangat dengan perpaduan rasa manis dan segar yang nikmat.'],
            ['name' => 'Es Sirup', 'price' => 4000, 'desc' => 'Minuman sirup manis dan segar yang disajikan dingin dengan es batu.'],
            ['name' => 'Es Cokelat', 'price' => 6000, 'desc' => 'Minuman cokelat manis dan creamy yang disajikan dingin, cocok untuk segala usia.'],
            ['name' => 'Es Lemon Tea', 'price' => 6000, 'desc' => 'Perpaduan teh dan lemon dengan rasa manis, segar, dan sedikit asam yang menyegarkan.'],
            ['name' => 'Es Milo', 'price' => 7000, 'desc' => 'Minuman cokelat malt yang manis dan creamy, disajikan dingin dengan es batu.'],
            ['name' => 'Es Susu', 'price' => 6000, 'desc' => 'Minuman susu segar dengan rasa creamy dan manis yang pas, cocok sebagai pelengkap hidangan.'],
            ['name' => 'Es Cappuccino', 'price' => 8000, 'desc' => 'Minuman kopi cappuccino dengan rasa creamy dan aroma kopi yang nikmat, disajikan dingin.'],
        ];

        foreach ($minumanList as $m) {
            Menu::updateOrCreate(['slug' => Str::slug($m['name'])], [
                'category_id' => $catMinuman->id,
                'name' => $m['name'],
                'description' => $m['desc'],
                'price' => $m['price'],
                'is_favorite' => false,
            ]);
        }

        $snackKeringList = [
            ['name' => 'Kacang Bawang', 'price' => 3000, 'desc' => 'Kacang tanah gurih dan renyah dengan aroma bawang yang harum, cocok sebagai camilan.'],
            ['name' => 'Keripik Tempe', 'price' => 3500, 'desc' => 'Irisan tempe tipis yang digoreng hingga renyah dengan bumbu gurih dan nikmat.'],
            ['name' => 'Keripik Singkong', 'price' => 3500, 'desc' => 'Keripik singkong renyah dengan bumbu gurih yang cocok dinikmati kapan saja.'],
            ['name' => 'Keripik Pisang', 'price' => 4000, 'desc' => 'Irisan pisang pilihan yang digoreng renyah dengan cita rasa manis dan gurih.'],
            ['name' => 'Stik Keju', 'price' => 4000, 'desc' => 'Camilan berbentuk stik dengan tekstur renyah dan rasa keju yang gurih.'],
            ['name' => 'Pastel Kering', 'price' => 4000, 'desc' => 'Pastel berukuran kecil dengan kulit renyah dan isian gurih yang cocok sebagai teman bersantai.'],
            ['name' => 'Cheese Stick', 'price' => 4000, 'desc' => 'Camilan stik renyah dengan rasa keju yang gurih dan aroma yang menggugah selera.'],
            ['name' => 'Kastengel', 'price' => 5000, 'desc' => 'Kue kering renyah dengan rasa keju yang gurih dan aroma keju yang khas.'],
        ];

        foreach ($snackKeringList as $sk) {
            Menu::updateOrCreate(['slug' => Str::slug($sk['name'])], [
                'category_id' => $catSnackKering->id,
                'name' => $sk['name'],
                'description' => $sk['desc'],
                'price' => $sk['price'],
                'is_favorite' => false,
            ]);
        }

        $snackBasahList = [
            ['name' => 'Risol Mayo', 'price' => 4000, 'desc' => 'Kulit risol lembut dengan isian mayones, telur, dan smoked beef yang gurih dan creamy.'],
            ['name' => 'Samosa', 'price' => 4000, 'desc' => 'Pastry berbentuk segitiga dengan isian sayuran berbumbu gurih, renyah di luar dan lezat di dalam.'],
            ['name' => 'Arem-Arem', 'price' => 3500, 'desc' => 'Nasi berbumbu yang dibungkus daun pisang dengan isian ayam dan sayuran, cocok sebagai camilan mengenyangkan.'],
            ['name' => 'Kue Sus', 'price' => 4000, 'desc' => 'Kue sus bertekstur lembut dengan isian vla manis dan creamy yang nikmat.'],
            ['name' => 'Lemper Ayam', 'price' => 4000, 'desc' => 'Ketan pulen dengan isian ayam berbumbu gurih yang dibungkus daun pisang.'],
            ['name' => 'Dadar Gulung', 'price' => 3500, 'desc' => 'Kue tradisional dengan kulit lembut dan isian kelapa manis yang legit.'],
            ['name' => 'Pastel', 'price' => 4000, 'desc' => 'Kulit pastel renyah dengan isian sayuran dan bihun berbumbu gurih.'],
            ['name' => 'Tahu Isi', 'price' => 3500, 'desc' => 'Tahu goreng renyah dengan isian sayuran berbumbu gurih dan nikmat.'],
            ['name' => 'Kroket Ayam', 'price' => 4000, 'desc' => 'Kroket lembut dengan isian kentang dan ayam berbumbu gurih, dibalut lapisan renyah.'],
            ['name' => 'Bolu Kukus', 'price' => 3500, 'desc' => 'Bolu kukus lembut dan empuk dengan rasa manis yang pas, cocok untuk segala suasana.'],
            ['name' => 'Klepon', 'price' => 3000, 'desc' => 'Kue tradisional berbahan tepung ketan dengan isian gula merah cair dan balutan kelapa parut.'],
            ['name' => 'Onde-Onde', 'price' => 3500, 'desc' => 'Kue bulat berlapis wijen dengan tekstur kenyal dan isian kacang hijau yang manis.'],
            ['name' => 'Nagasari', 'price' => 3500, 'desc' => 'Kue lembut berbahan tepung beras dengan isian pisang, dibungkus daun pisang dan dikukus hingga matang.'],
            ['name' => 'Pisang Cokelat', 'price' => 4000, 'desc' => 'Pisang manis yang dibalut kulit renyah dengan isian cokelat yang lumer dan nikmat.'],
        ];

        foreach ($snackBasahList as $sb) {
            Menu::updateOrCreate(['slug' => Str::slug($sb['name'])], [
                'category_id' => $catSnackBasah->id,
                'name' => $sb['name'],
                'description' => $sb['desc'],
                'price' => $sb['price'],
                'is_favorite' => false,
            ]);
        }

        // 5. Seed Packages
        $mNasi = Menu::where('category_id', $catNasiBox->id)->first();
        $mSnack = Menu::where('category_id', $catSnackBasah->id)->first();
        $mMinum = Menu::where('category_id', $catMinuman->id)->first();

        if ($mNasi && $mSnack && $mMinum) {
            $p1 = Package::updateOrCreate(['name' => 'Paket Hemat Mahasiswa'], [
                'description' => 'Kombinasi ekonomis menu pilihan terbaik untuk 1 orang.',
                'price' => $mNasi->price + $mSnack->price + $mMinum->price,
            ]);

            PackageDetail::updateOrCreate(['package_id' => $p1->id, 'menu_id' => $mNasi->id]);
            PackageDetail::updateOrCreate(['package_id' => $p1->id, 'menu_id' => $mSnack->id]);
            PackageDetail::updateOrCreate(['package_id' => $p1->id, 'menu_id' => $mMinum->id]);
        }

        // 6. Settings Apriori (sesuai parameter skripsi: Support 20%, Confidence 50%)
        Setting::setValue('min_support', '0.2'); 
        Setting::setValue('min_confidence', '0.5'); 

        // 7. GENERATE TRANSAKSI MASIF AGAR 4-ITEMSET MUNCUL 50+ KOMBINASI
        $allCustomers = User::where('role', 'customer')->get();

        $nasiMenus = Menu::where('category_id', $catNasiBox->id)->get();
        $snackBasahMenus = Menu::where('category_id', $catSnackBasah->id)->get();
        $snackKeringMenus = Menu::where('category_id', $catSnackKering->id)->get();
        $minumanMenus = Menu::where('category_id', $catMinuman->id)->get();

       foreach ($allCustomers as $cust) {
    $jumlahPesanan = rand(60, 80);
            for ($i = 0; $i < $jumlahPesanan; $i++) {
                $orderDate = Carbon::now()->subDays(rand(1, 60));

                $order = Order::create([
                    'user_id' => $cust->id,
                    'order_code' => 'ORD-' . strtoupper(Str::random(6)),
                    'order_date' => $orderDate,
                    'total_price' => 0, 
                    'status' => 'completed',
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                // Kombinasi 4 kategori menu diambil secara acak murni dari seluruh item yang tersedia
                $selectedMenus = collect([
                    $nasiMenus->random(),
                    $snackBasahMenus->random(),
                    $snackKeringMenus->random(),
                    $minumanMenus->random(),
                ]);

                $totalOrderPrice = 0;

                foreach ($selectedMenus->unique('id') as $menu) {
                    $qty = rand(20, 50); 
                    $subtotal = $menu->price * $qty;
                    $totalOrderPrice += $subtotal;

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'menu_id' => $menu->id,
                        'qty' => $qty,
                        'price' => $menu->price,
                        'total' => $subtotal,
                    ]);
                }

                $order->update(['total_price' => $totalOrderPrice]);
            }
        }
    }
}