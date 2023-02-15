<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Index extends BaseController
{

    public $loginBehavior = false;
    public $template = "app_front";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_master');
    }

    public function index()
    {
        $this->data['title'] = 'Beranda';
        $this->data['is_full'] = true;
        $this->data['fiturs'] = $this->M_master->listFitur();
        $this->data['faqs'] = $this->M_master->listFaq();
        $this->render("index");
    }

    public function program()
    {
        $this->data['title'] = 'Program';
        $this->data['is_full'] = true;
        $this->render("program");
    }

    public function berita()
    {
        $this->data['title'] = 'Berita';
        $this->data['is_full'] = true;
        $this->render("berita");
    }

    public function pengumuman()
    {
        $this->data['title'] = 'Berita';
        $this->data['is_full'] = true;
        $this->render("berita");
    }

    
    public function notFound()
	{
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$isBrowser = $this->isBrowser($userAgent);
		if ($isBrowser) {
			http_response_code(404);
            $this->template = 'notfound';
			$this->data['title'] = 'Page Not Found';
            $this->data['is_full'] = true;
			$this->render('notfound');
		} else {
			header("Content-Type: application/json");
			http_response_code(404);
			echo json_encode([
				'code'     	=> 404,
				'message'    => 'Not found',
                'error' => null,
				'data'       => null,
			]);
			die;
		}
	}

    private function isBrowser($userAgent)
	{
		$isBrowser = false;
		$isBrowser = isBrowser($userAgent);

		return $isBrowser;
	}
}
