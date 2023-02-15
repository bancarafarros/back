<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

use Restserver\Libraries\REST_Controller;

class Referensi extends REST_Controller
{


    private $ok = '200';
    private $bad = '400';
    private $unauthorized = '401';
    private $notfound = '404';
    private $error = '500';

    function __construct()
    {
        parent::__construct();
        $this->methods['data_post']['limit'] = 100; // 100 requests per hour per data/key
        $this->load->model('api/api_provinsi', 'provinsi');
        $this->load->model('api/Api_referensi', 'referensi');
    }

    public function typologi_get()
    {
        $id         = $this->input->get('id');
        $type_for   = $this->input->get('type_for');

        $current = $this->referensi->typologi($id, $type_for);
        if ($current['status'] == 201) {
            $this->response([
                'code'      => REST_Controller::HTTP_OK,
                'message'   => 'Data typografi ditemukan',
                'data'      => $current['data'],
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'code'      => REST_Controller::HTTP_NO_CONTENT,
                'message'   => 'Database Kosong',
                'data'      => [],
            ], REST_Controller::HTTP_NO_CONTENT);
        }
    }
    public function afiliasi_get()
    {

        $current = $this->referensi->afiliasi();
        if ($current['status'] == 201) {
            $this->response([
                'code'      => REST_Controller::HTTP_OK,
                'message'   => 'Data typografi ditemukan',
                'data'      => $current['data'],
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'code'      => REST_Controller::HTTP_NO_CONTENT,
                'message'   => 'Database Kosong',
                'data'      => [],
            ], REST_Controller::HTTP_NO_CONTENT);
        }
    }
    public function statusTanah_get()
    {

        $current = $this->referensi->statusTanah();
        if ($current['status'] == 201) {
            $this->response([
                'code'      => REST_Controller::HTTP_OK,
                'message'   => 'Data typografi ditemukan',
                'data'      => $current['data'],
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'code'      => REST_Controller::HTTP_NO_CONTENT,
                'message'   => 'Database Kosong',
                'data'      => [],
            ], REST_Controller::HTTP_NO_CONTENT);
        }
    }


    // get all provinsi
    public function provinsi_get()
    {
        $provinsi = $this->provinsi->get_all();

        if ($provinsi->num_rows() > 0) {

            $this->response([
                'code'      => REST_Controller::HTTP_OK,
                'message'   => 'Berhasil Diambil',
                'data'      => $provinsi->result(),
            ], REST_Controller::HTTP_OK);
            return;
        } else {
            $this->response([
                'code'      => REST_Controller::HTTP_NO_CONTENT,
                'message'   => 'Database Kosong',
                'data'      => null,
            ], REST_Controller::HTTP_NO_CONTENT);
        }
    }

    // get all kabupaten
    public function kabupaten_get($kode_provinsi = '')
    {
        $kabupaten = $this->referensi->getKabupaten($kode_provinsi);

        if ($kabupaten['status']== 201) {

            $this->response([
                'code'      => REST_Controller::HTTP_OK,
                'message'   => 'Berhasil Diambil',
                'data'      => $kabupaten['data'],
            ], REST_Controller::HTTP_OK);
            return;
        } else {
            $this->response([
                'code'      => REST_Controller::HTTP_NO_CONTENT,
                'message'   => 'Database Kosong',
                'data'      => null,
            ], REST_Controller::HTTP_NO_CONTENT);
            return;
        }
    }

    // get kabupaten by id provinsi
    public function kabupatenByProvinsi_post()
    {
        $kode_pronvisi = $this->input->post('kode_provinsi');
        $kabupaten = $this->kabupaten->getByProvinsi($kode_pronvisi);

        if ($kabupaten->num_rows() > 0) {
            $this->response([
                'code'      => REST_Controller::HTTP_OK,
                'message'   => 'Berhasil Diambil',
                'data'      => $kabupaten->result(),
            ], REST_Controller::HTTP_OK);
            return;
        } else {
            $this->response([
                'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                'message'   => 'Data Tidak Ditemukan',
                'data'      => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }

    // get all kecamatan
    public function kecamatan_get($kode_kabupaten = '')
    {
        $kecamatan = $this->referensi->getKecamatan($kode_kabupaten);

        if ($kecamatan['status'] == 201) {

            $this->response([
                'code'      => REST_Controller::HTTP_OK,
                'message'   => 'Berhasil Diambil',
                'data'      => $kecamatan['data'],
            ], REST_Controller::HTTP_OK);
            return;
        } else {
            $this->response([
                'code'      => REST_Controller::HTTP_NO_CONTENT,
                'message'   => 'Database Kosong',
                'data'      => null,
            ], REST_Controller::HTTP_NO_CONTENT);
        }
    }

    // get kecamatan by id kabupaten
    public function kecamatanByKabupaten_post()
    {
        $kode_kabupaten = $this->input->post('kode_kabupaten');
        $kecamatan = $this->kecamatan->getByKabupaten($kode_kabupaten);

        if ($kecamatan->num_rows() > 0) {
            $this->response([
                'code'      => REST_Controller::HTTP_OK,
                'message'   => 'Berhasil Diambil',
                'data'      => $kecamatan->result(),
            ], REST_Controller::HTTP_OK);
            return;
        } else {
            $this->response([
                'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                'message'   => 'Data Tidak Ditemukan',
                'data'      => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }

    public function index_post()
    {
        $this->response([
            'status' => $this->bad,
            'error' => 'Bad Request'
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_get()
    {
        $this->response([
            'status' => $this->bad,
            'error' => 'Bad Request'
        ], REST_Controller::HTTP_BAD_REQUEST);
    }
}
