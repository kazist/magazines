<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Magazines\Magazines\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Json\Json;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class MagazinesModel extends BaseModel {

    public function getQueryBuilder() {

        $factory = new KazistFactory();

        $subscription_only = $factory->getSetting('magazine_magazine_limit_subscription');

        $query = parent::getQueryBuilder('#__magazines_magazines', 'mm');

        if ($subscription_only) {
            $user_magazine_ids = $this->getUserMagazineIds();

            if (!empty($user_magazine_ids)) {
                $query->where('mm.id IN (' . implode(',', $user_magazine_ids) . ')');
            } else {
                $query->where('1=-1');
            }
        }

        return $query;
    }

    public function getRelatedMagazine($magazine_id) {

        $json = new Json();


        $query = new Query();
        $query = $this->getQueryBuilder('#__magazines_magazines', 'mm');

        if ($magazine_id) {
            $query->where('mm.id<>:magazine_id');
            $query->setParameter('magazine_id', $magazine_id);
        }


        $query->setFirstResult(0);
        $query->setMaxResults(5);
        $records = $query->loadObjectList();

        return $records;
    }

    public function getCategoryMagazines($category_id) {

        $json = new Json();
        $factory = new KazistFactory();

        $user = $factory->getUser();

        $query = new Query();
        $query = $this->getQueryBuilder('#__magazines_magazines', 'mm');

        if ($category_id) {
            $query->where('mm.category_id=:category_id');
            $query->setParameter('category_id', $category_id);
        } else {
            $query->where('1=-1');
        }

        $query->setFirstResult(0);
        $query->setMaxResults(5);
        $records = $query->loadObjectList();

        return $records;
    }

    public function getUserMagazineIds() {

        $tmparray = array();

        $factory = new KazistFactory();

        $user = $factory->getUser();

        $query = new Query();
        $query->select('mmu.magazine_id');
        $query->from('#__magazines_magazines_users as mmu');
        $query->where('mmu.user_id=:user_id');
        $query->setParameter('user_id', $user->id);


        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmparray[] = $record->magazine_id;
            }
        }

        return $tmparray;
    }

}
