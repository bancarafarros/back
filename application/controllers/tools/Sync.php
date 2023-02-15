<?php

/**
 * Created by PhpStorm
 * User : Bambang S
 * Date : 09-04-2021
 * Time: 22:07
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use app\libraries\Arkatama\crypto\Bijective;

class Sync extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function setCatPassword()
    {
        //php index.php tools/Sync setCatPassword
        echo "Set student password.." . PHP_EOL;
        $result  = $this->db
            ->select(['id_formulir', 'nik'])
//            ->where(['password' => null])
            ->get('lolos_berkas')
            ->result();
        $encoder = new Bijective();
        if ($result) {
            foreach ($result as $r) {
                $hash    = $encoder->encode($r->nik);
                $decoded = $encoder->decode($hash);
                echo $hash . "|" . $decoded . "=" . substr($r->nik, 0, 8) . "\n";
                $password = password_hash($hash, PASSWORD_BCRYPT, ['cost' => 4]);
                $this->db->set(['password' => $password])->where(['id_formulir' => $r->id_formulir])->update('lolos_berkas');
            }
        }
        echo "Set student password finished" . PHP_EOL;

    }

}