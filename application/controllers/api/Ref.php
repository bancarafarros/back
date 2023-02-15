<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

use Restserver\Libraries\REST_Controller;

class Ref extends REST_Controller {


    private $ok = '200';
    private $bad = '400';
    private $unauthorized = '401';
    private $notfound = '404';
    private $error = '500';

    function __construct() {
        parent::__construct();
        $this->methods['data_post']['limit'] = 100; // 100 requests per hour per data/key
        $this->load->model('api/api_provinsi', 'provinsi');
        $this->load->model('api/api_kabupaten', 'kabupaten');
        $this->load->model('api/api_kecamatan', 'kecamatan');
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
    public function kabupaten_get()
    {
        $kabupaten = $this->kabupaten->get_all();

        if ($kabupaten->num_rows() > 0) {

            $this->response([
                'code'      => REST_Controller::HTTP_OK,
                'message'   => 'Berhasil Diambil',
                'data'      => $kabupaten->result(),
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
    public function kecamatan_get()
    {
        $kecamatan = $this->kecamatan->get_all();

        if ($kecamatan->num_rows() > 0) {

            $this->response([
                'code'      => REST_Controller::HTTP_OK,
                'message'   => 'Berhasil Diambil',
                'data'      => $kecamatan->result(),
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

    


}

