<?php

/**
 * Created by PhpStorm
 * User : Bambang S
 * Date : 02-05-2021
 * Time: 04:07
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class QuestionParser extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'libraries/PHPExcel/IOFactory.php';

    }

    public function Parse()
    {
        $file_path      = 'C:\Users\c0d3t\Desktop\SOAL\2 KEWIRAUSAHAAN_Hermawan_Detia_Rev.xlsx';
        $levelEasyId    = '10';
        $levelMediumId  = '11';
        $objPHPExcel    = PHPExcel_IOFactory::load($file_path);
        $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        $arrayCount     = count($allDataInSheet);
        for ($i = 2; $i <= $arrayCount; $i++) {
            $no       = $allDataInSheet[$i]['A'];
            $question = $allDataInSheet[$i]['B'];
            $correct  = $allDataInSheet[$i]['H'];

            $complex  = $allDataInSheet[$i]['I'];
            $level    = $complex == '1' ? $levelEasyId : $levelMediumId;
            $question = [
                'question_category_id' => $level,
                'question_type'        => 'single',
                'question_text'        => $question,
                'question_enabled'     => 'true'
            ];


            echo $i . "\n";
            $this->db->insert('questions', $question);
            $questionId = $this->db->insert_id();
            foreach (range('C', 'G') as $v) {
                $xlsAnswerPosition = ord(strtoupper($v)) - ord('C') + 1;
                $answerLabel       = chr(64 + $xlsAnswerPosition);
                $isCorrect         = 'false';
                if ($answerLabel === $correct) {
                    $isCorrect = 'true';
                }
                $answer = [
                    'answer_question_id' => $questionId,
                    'answer_text'        => $allDataInSheet[$i][$v],
                    'answer_is_correct'  => $isCorrect,
                    'answer_enabled'     => 'true'
                ];
                $this->db->insert('answers', $answer);
            }

        }

    }

}