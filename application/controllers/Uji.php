<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Uji extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_masjid', 'masjid');
        $this->load->model('M_pengurus', 'pengurus');
    }

    private function index()
    {
        setSession('coba', 'coba');
        echo getSession('coba');
        unsetSession('coba');

        echo 'bawah';
        echo getSession('coba');
    }

    public function send()
    {
        // ob_start();
        $phone = '6285103473402';
        $first = substr($phone, 0, 1);
        if ($first == 0) {
            $panjang = strlen($phone);
            $phone = '62' . substr($phone, - ($panjang - 1));
        }

        // echo $phone;
        // sendWANotifikasi($phone);
        $text = 'Ini push WA dari server IAIN Ponorogo ' . site_url('uji/send') . ' pada :' . date('Y-m-d H:i:s');
        $jsonData = [
            'api_key'   => getSystemSetting('ws_api_key'),
            'phone'     => $phone,
            'text'      => messageWaBody('Ulum Miftahul', $text),
            // 'media_url' => 'https://pmb.pusdiktan.id/public/portal/skin/img/banner-hero.png'
        ];
        // echo '<pre>';
        // print_r($jsonData);
        // echo '</pre>';
        // die;
        $url = getSystemSetting('ws_url_send');
        $data = dswa_request($url, $jsonData);
    }

    public function akun()
    {
        $phone = '6285103473402';
        $password = generatePassword($phone);
        $idTimeline = '00043c7b-9c8f-11ec-8c04-3a3343e72uu5';
        $idProdiSatu = '00042efb-9c8f-11ec-8c04-3a3343eeko11';
        $nomor =  generateNoPendaftaran($idTimeline, $idProdiSatu);
        $dataSend = ['no_hp' => $phone, 'nama' => 'Miftahul Ulum', 'no_pendaftaran' => $nomor, 'password' => $password];
        $send = sendNotifikasiAkunAdmisi($dataSend);
        echo '<pre>';
        print_r($send);
        echo '</pre>';
        die;
    }

    public function wamultiple()
    {
        $datas = [
            'phones' => [
                '6285103473402', '6282244100442',
                // '6285735209127', '6281249223696',
                // '6285104116711', '6281249553534',
                // '6281913201504', '6285607932782',
                // '6285721493382', '6285748858086',
            ],
            'names' => [
                'Liya Putri', 'Miftahul Ulum',
                // 'tiga', 'empat',
                // 'lima', 'enam',
                // 'tujuh', 'delapan',
                // 'nama saya', 'nama kami',
            ]
        ];
        $dataSend = [];
        for ($i = 0; $i < count($datas['phones']); $i++) {
            $dataSend[] = [
                'phone' => $datas['phones'][$i],
                'text' => messageWaBody($datas['names'][$i], 'Percobaan multiple send wa' . ' ' . date('Y-m-d H:i:s') . "\n" . site_url('uji/wamultiple'), true),
                // 'media_url' => 'https://pmb.pusdiktan.id/public/portal/skin/img/banner-hero.png'
            ];
        }
        sendWaMultiple($dataSend);
    }

    public function nopendaftaran()
    {
        $idTimeline = '00043c7b-9c8f-11ec-8c04-3a3343e72uu5';
        $idProdiSatu = '00042efb-9c8f-11ec-8c04-3a3343eeko11';
        $nomor =  generateNoPendaftaran($idTimeline, $idProdiSatu);
        echo '<pre>';
        print_r($nomor);
        echo '</pre>';
        die;
        return $nomor;
    }

    public function activeMenu()
    {
        // $data = getDynamicNavMenus();
        // $data = getParentMenus(getSession('idGroup'));
        $data = getDynamicMenus();
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }

    public function restrict()
    {
        $data = redirectNotAccess('pendaftaran', 'dashboard');
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }

    public function uuid()
    {
        $data = getUUID();
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }

    public function timeline()
    {
        $idTimeline = '00043c7b-9c8f-11ec-8c04-3a3343e72uu5';
        $data = getTimelineData($idTimeline);
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }

    public function captcha()
    {
        // $cap = createCaptcha();
    }


    public function menus()
    {
        $menus = active_submenu('dashboard/pendaftaran/kartuujian');
        echo '<pre>';
        print_r($menus);
        echo '</pre>';
        die;
    }

    public function jalur()
    {
        $jalurs = timelineJalur();
        echo '<pre>';
        print_r($jalurs);
        echo '</pre>';
        die;
    }

    public function virtualAccount()
    {
        $this->db->where(['id_pendaftar' => '87a0afc8-257a-4bbb-bc07-d92dbf71b5bd']);
        $pendaftar = $this->db->get(SCHEMA_PMB . '.pendaftar')->row_array();
        $pendaftar['no_wa'] = '62' . $pendaftar['no_hp'];
        $this->load->library('Bni/Bnilib', null, 'bnilib');
        $trx = $this->bnilib->getTrxId();
        // echo '<pre>';
        // print_r($trx);
        // echo '</pre>';
        // die;
        // $noPendaftaran = '9881905522320005';
        $create = $this->bnilib->createVAPendaftaran($pendaftar);
        // $inquiry = $this->bnilib->inquiryVA(3);
        echo '<pre>';
        print_r($create);
        echo '</pre>';
        die;
    }

    public function trxId()
    {
        $this->db->where(['id_pendaftar' => '87a0afc8-257a-4bbb-bc07-d92dbf71b5bd']);
        $pendaftar = $this->db->get(SCHEMA_PMB . '.pendaftar')->row_array();
        $this->load->library('Bni/Bnilib', null, 'bnilib');
        $trx = $this->bnilib->getTrxId();
        // echo '<pre>';
        // print_r($trx);
        // echo '</pre>';
        // die;
        // $noPendaftaran = '9881905522320005';
        // $create = $this->bnilib->createVAPendaftaran($pendaftar);
        // $inquiry = $this->bnilib->inquiryVA(3);
        echo '<pre>';
        print_r($trx);
        echo '</pre>';
        die;
    }

    public function inquiry()
    {
        $trxId = $this->input->get('trxid');
        if (empty($trxId)) {
            echo 'Trx Id kosong';
            die;
        }
        $this->load->library('Bni/Bnilib', null, 'bnilib');
        // $trx = $this->bnilib->getTrxId();
        // echo '<pre>';
        // print_r($trx);
        // echo '</pre>';
        // die;
        // $create = $this->bnilib->createVAPendaftaran($this->nopendaftaran());
        $inquiry = $this->bnilib->inquiryVA($trxId);
        echo '<pre>';
        print_r($inquiry);
        echo '</pre>';
        echo json_encode($inquiry['data']);
        die;
    }

    public function updateVa()
    {
        $trxId = $this->input->get('trxid');
        $trxAmount = $this->input->get('amount');
        if (empty($trxId) || empty($trxAmount)) {
            echo 'parameter kosong';
            die;
        }
        $this->load->library('Bni/Bnilib', null, 'bnilib');
        $data = ['nama' => 'Miftahul Ulum', 'email' => 'oke@mail.com', 'no_hp' => '85103473402', 'description' => 'Ubahan baru'];
        $update = $this->bnilib->updateVAPendaftaran($trxId, $trxAmount, $data);
        echo '<pre>';
        print_r($update);
        echo '</pre>';
        die;
    }

    public function callBack()
    {
        $this->load->model('M_pendaftar', 'm_pendaftar');
        $phone = '6285103473402';
        $this->load->library('Bni/Bnilib', null, 'bnilib');
        // URL utk simulasi pembayaran: http://dev.bni-ecollection.com/
        // $data = file_get_contents('php://input');
        // $data_json = json_decode($data, true);
        $data = file_get_contents(base_url('public/hasil_callback_va.json'));
        $data_json = json_decode($data, true);
        // $tangkap = $this->bnilib->callBack($data_json);
        $tangkap['success'] = true;
        $tangkap['data'] = $data_json;
        sendWa($phone, json_encode($tangkap));
        if ($tangkap['success']) {
            sendWa($phone, 'callback library berhasil');
            $pendaftar = $this->m_pendaftar->pendaftarByTrxId($tangkap['data']['trx_id']);
            $proses = $this->m_pendaftar->setPesertaByPayment($pendaftar, $tangkap['data']);
            sendWa($phone, 'setelah ke set peserta');
            if ($proses['status']) {
                sendWa($phone, 'set peserta berhasil');
                $dataSend = $proses['data'];
                $phonecode = getCountryById($dataSend['phone_country_id'])['phonecode'];
                $no_hp = $phonecode . $dataSend['no_hp'];
                $dataSend['no_hp'] = $no_hp;
                $dataSend['password'] = $dataSend['password'];
                sendNotifikasiAkunAdmisi($dataSend);
            } else {
                sendWa($phone, 'set peserta gagal ' . json_encode($proses['error']));
            }
            sendWa($phone, 'proses callback oke' . '\n' . json_encode($tangkap['data']));
        } else {
            sendWa($phone, 'tidak berhasil' . '\n' . json_encode($tangkap));
        }
    }

    private function setcamaba()
    {
        $this->load->model('M_peserta', 'm_peserta');
        $id_peserta = 'be05c4ff-ff7f-4a3e-bc88-0ac59a062ec0';
        $cek = $this->db->where(['id_peserta' => $id_peserta])->get(SCHEMA_PMB . '.camaba')->row_array();
        if (!empty($cek)) {
            echo 'sudah ada';
            die;
        }
        // $this->db->debug = false;
        $peserta = $this->m_peserta->pesertaData($id_peserta);

        $idCamaba = getUUID();
        $camaba = [
            'id_camaba' => $idCamaba,
            'id_peserta' => $peserta['id_peserta'],
            'nama' => $peserta['nama'],
            'nik' => $peserta['nik'],
            'tempat_lahir' => $peserta['tempat_lahir'],
            'tanggal_lahir' => $peserta['tanggal_lahir'],
            'no_hp' => $peserta['no_hp'],
            'email' => $peserta['email'],
            'jenis_kelamin' => $peserta['jenis_kelamin'],
            'kode_provinsi' => $peserta['kode_provinsi'],
            'kode_kabupaten' => $peserta['kode_kabupaten'],
            'kode_kecamatan' => $peserta['kode_kecamatan'],
            'country_id' => $peserta['country_id'],
            'id_jalur_masuk' => $peserta['id_jalur_masuk'],
            'phone_country_id' => $peserta['phone_country_id'],
            'id_user' => $peserta['id_user'],
            'id_prodi_diterima' => $peserta['id_prodi_diterima'],
            'kewarganegaraan' => $peserta['kewarganegaraan'],
            'alamat' => $peserta['alamat'],
            'jenjang' => $peserta['jenjang'],
            'id_prodi_pilihan1' => $peserta['id_prodi_pilihan1'],
            'id_prodi_pilihan2' => $peserta['id_prodi_pilihan2'],
            'url_photo' => $peserta['url_photo'],
            'id_prodi_pilihan3' => $peserta['id_prodi_pilihan3'],
            'no_pendaftaran' => $peserta['no_pendaftaran'],
        ];

        $this->db->where(['id_peserta' => $id_peserta]);
        $prestasi = $this->db->get(SCHEMA_PMB . '.peserta_prestasi')->result_array();
        $prestasiBaru = [];
        if (!empty($prestasi)) {
            foreach ($prestasi as $p => $pres) {
                $prestasiBaru[] = [
                    'id_camaba' => $idCamaba,
                    'nama_prestasi' => $pres['nama_prestasi'],
                    'tingkat' => $pres['tingkat'],
                    'tahun' => $pres['tahun'],
                    'url_dokumen' => $pres['url_dokumen'],
                    'status' => $pres['status'],
                    'juara' => $pres['juara'],
                ];
            }
        }

        $this->db->where(['id_peserta' => $id_peserta]);
        $instansi = $this->db->get(SCHEMA_PMB . '.peserta_instansi')->row_array();
        $instansiBaru = [];
        if (!empty($instansi)) {
            $instansiBaru = [
                'id_camaba' => $idCamaba,
                'nomor' => $instansi['nomor'],
                'nama_instansi' => $instansi['nama_instansi'],
                'akreditasi' => $instansi['akreditasi'],
                'jurusan' => $instansi['jurusan'],
                'tahun_lulus' => $instansi['tahun_lulus'],
                'kode_provinsi_instansi' => $instansi['kode_provinsi_instansi'],
                'kode_kabupaten_instansi' => $instansi['kode_kabupaten_instansi'],
                'kode_kecamatan_instansi' => $instansi['kode_kecamatan_instansi'],
                'alamat_instansi' => $instansi['alamat_instansi'],
                'nilai' => $instansi['nilai'],
                'jenis_instansi' => $instansi['jenis_instansi'],
                'no_ijazah' => $instansi['no_ijazah'],
            ];
        }

        // echo '<pre>';
        // print_r($camaba);
        // echo '---------<br>';
        // print_r($prestasiBaru);
        // echo '---------<br>';
        // print_r($instansiBaru);
        // echo '</pre>';
        // die;
        $this->db->trans_start();
        $this->db->insert(SCHEMA_PMB . '.camaba', $camaba);

        $this->db->insert(SCHEMA_PMB . '.camaba_instansi', $instansiBaru);

        $this->db->insert_batch(SCHEMA_PMB . '.camaba_prestasi', $prestasiBaru);

        $this->db->where(['id_user' => $peserta['id_user']]);
        $this->db->update(SCHEMA_PMB . '.user', ['id_group' => '100']);

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo 'gagal';
        }
        $this->db->trans_commit();
        echo 'commit';
    }

    public function gelombang()
    {
        $idJalurMasuk = '00043c7b-9c8f-11ec-8c04-3a3343e7324 ';
        $data = getGelombang($idJalurMasuk);
        print_r($data);
    }

    public function timelineJalur()
    {
        $this->load->model('M_jalur_masuk', 'm_jalur');
        $idJalurMasuk = '00043c7b-9c8f-11ec-8c04-3a3343e72765';
        $tglAwal = '2022-10-05';
        $tglAkhir = '2022-10-20';
        $data = $this->m_jalur->timelineDataByTanggal($idJalurMasuk, $tglAwal, $tglAkhir);
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }


    private function sikatdarurat()
    {
        // $dat = $this->db->get(SCHEMA_AKADEMIK . '.feeder_data_dosen')->result_array();
        $tableName = SCHEMA_AKADEMIK . '.feeder_data_dosen';
        $data  = file_get_contents('./public/feeder_data_dosen.json');
        $end = json_decode($data, true);
        foreach ($end as $key => $d) {

            foreach ($d as $key => $dosen) {
                $idDosen = $dosen['id_sdm'];
                $namaDosen = $dosen['nm_sdm'];
                $nidn = $dosen['nidn'];
                $this->db->where(['nama_dosen' => $namaDosen, 'nidn' => $nidn]);
                $this->db->update($tableName, ['id_dosen' => $idDosen]);
            }
        }
    }

    public function browser()
    {
        $agent = null;
        if ($this->agent->is_browser()) {
            $agent = $this->agent->browser() . ' ' . $this->agent->version();
        }
        echo '<pre>';
        print_r($agent);
        echo '</pre>';
        die;
    }

    public function insert()
    {
        $masjid = $this->db
            ->select('m.*, prov.nama_provinsi as provinsi, kab.nama_kab_kota as kabupaten, kec.nama_kecamatan as kecamatan, tan.name as status_tanah, aff.name as afiliasi, typo.name as typologi')
            ->from('masjid m')
            ->join('ref_provinsi prov', 'prov.kode_provinsi = m.kode_provinsi', 'left')
            ->join('ref_kabupaten kab', 'kab.kode_kab_kota  = m.kode_kabupaten', 'left')
            ->join('ref_kecamatan kec', 'kec.kode_kecamatan = m.kode_kecamatan', 'left')
            ->join('ref_status_tanah tan', 'tan.id = m.ref_id_status_tanah', 'left')
            ->join('ref_typologi typo', 'typo.id = m.id_ref_typologi', 'left')
            ->join('ref_afiliasi aff', 'aff.id = m.ref_id_afiliasi', 'left')
            ->order_by('m.nama', 'ASC')
            ->get()
            ->result_array();
        foreach ($masjid as $key => $value) {
            $pengurus['id'] = getUUID();
            $pengurus['id_masjid'] = $value['id'];
            $pengurus['id_user'] = $value['id_user'];
            $pengurus['nama'] = $value['nama_pj_takmir'];
            $pengurus['jenis_kelamin'] = 'Laki-laki';
            $pengurus['jabatan'] = $value['jabatan_takmir'];
            $pengurus['no_hp'] = $value['no_hp'];
            $pengurus['email'] = $value['email'];
            $pengurus['kode_provinsi'] = $value['kode_provinsi'];
            $pengurus['kode_kabupaten'] = $value['kode_kabupaten'];
            $pengurus['kode_kecamatan'] = $value['kode_kecamatan'];
            $pengurus['created_by'] = getSessionID();
            $cekPengurus = $this->db->get_where('masjid_pengurus', array('nama' => $pengurus['nama']))->num_rows();
            // var_dump($cekPengurus);
            // echo $cekPengurus;
            if ($cekPengurus == 0) {
                $result = $this->db->insert('masjid_pengurus', $pengurus);
                if ($result) {
                    echo "Sukses";
                } else {
                    echo "Gagal";
                }
            }
        }
    }
}
