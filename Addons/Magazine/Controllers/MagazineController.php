<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Magazines\Addons\Magazine\Controllers;

use Kazist\Controller\AddonController;
use Magazines\Addons\Magazine\Models\MagazineModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class MagazineController extends AddonController {

    function indexAction($offset = 0, $limit = 6) {

        $model = new MagazineModel;

        $magazine = $model->getMagazine();

        $data_arr['magazine'] = $magazine;

        $this->html = $this->render('Magazines:Addons:Magazine:views:magazine.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
