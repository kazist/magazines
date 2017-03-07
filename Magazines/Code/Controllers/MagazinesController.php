<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of MagazinesController
 *
 * @author sbc
 */

namespace Magazines\Magazines\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Controller\BaseController;

class MagazinesController extends BaseController {

    public function detailAction() {

        $view = $this->request->get('view');
        $id = $this->request->get('id');

        if ($view == 'preview') {

            $item = $this->model->getRecord($id);

            $data_arr['item'] = $item;

            $this->html = $this->render('Magazines:Magazines:Code:views:preview.index.twig', $data_arr);

            print_r($this->html);
            exit;
        } else {
            return parent::detailAction($id);
        }
    }

    public function downloadAction() {

        $factory = new KazistFactory();

        $id = $this->request->get('id');
        $item = $this->model->getRecord($id);

        $user_mag_ids = $this->model->getUserMagazineIds();
        $user_mag_ids[] = $this->model->getNewMagazineId();

        if (in_array($id, $user_mag_ids)) {

            $path = JPATH_ROOT . $item->file_file;
            $path_arr = array_reverse(explode('/', $path));
            $filename = $path_arr[0];

            header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
            header('Accept-Ranges: bytes');  // Allow support for download resume
            header('Content-Length: ' . filesize($path));  // File size
            header('Content-Encoding: none');
            header('Content-Type: application/pdf');  // Change the mime type if the file is not PDF
            header('Content-Disposition: attachment; filename=' . $filename);  // Make the browser display the Save As dialog
            readfile($path);

            exit;
        } else {
            $factory->enqueueMessage('This Magazine is not available for download because your subscription was not active during the release.', 'error');
            return $this->redirectToRoute('magazines.magazines.detail', array('id' => $id));
        }
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXX All Crons

    function cronaddusermagazineAction() {

        $this->model->addUserMagazine();

        return $this->redirectToRoute('magazines.magazines');
    }

}
