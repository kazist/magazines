<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Magazines\Addons\Magazine\Models;

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class MagazineModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getMagazine() {

        $query = new Query();

        $query->select('mag.*, mm.file as magazine_image');
        $query->from('#__magazines_magazines', 'mag');
        $query->leftJoin('mag', '#__media_media', 'mm', 'mm.id = mag.image');
        $query->where('mag.published=1');
        $query->orderBy('mag.id', '', 'DESC');

        $record = $query->loadObject();

        return $record;
    }

}
