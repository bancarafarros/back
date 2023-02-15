<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
* CAT response mode
 *          limited => limited api response related with college id
 *          full    => full no limited response
*/

$config['cat_mode'] = 'limited';

$config['cat_limit_api'] = false;

$config['cat_rate_limit'] = 200;