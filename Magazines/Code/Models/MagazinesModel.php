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
use Kazist\Service\Email\Email;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class MagazinesModel extends BaseModel {

    public function appendSearchQuery($query) {
        $query->orderBy('date_issued', 'DESC');
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
        $query->from('#__magazines_magazines_users ', ' mmu');
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

    public function getNewMagazineId() {

        $tmparray = array();

        $factory = new KazistFactory();

        $user = $factory->getUser();

        $query = new Query();
        $query->select('mm.id');
        $query->from('#__magazines_magazines', ' mm');
        $query->orderBy('mm.id', 'DESC');

        $record = $query->loadObject();

        return $record->id;
    }

    /* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  Add User Magazines xxxxxxxxxxxxxxxxxxxxxxxxx */

    public function getSubscriberList() {

        $query = new Query();

        $latest_magazine = $this->getLatestMagazine();

        $query->select('ss.*, uu.name, uu.username, uu.email, mmu.id');
        $query->from('#__users_users', 'uu');
        $query->leftJoin('uu', '#__subscriptions_subscriptions', 'ss', 'uu.id = ss.user_id');
        $query->leftJoin('ss', '#__magazines_magazines_users', 'mmu', 'mmu.user_id = ss.user_id AND mmu.magazine_id=' . $latest_magazine->id);
        $query->andWhere('ss.expiry_date>now()');
        $query->andWhere('mmu.id IS NULL');

        $query->setMaxResults(500);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getLatestMagazine() {

        $query = $this->getQueryBuilder('#__magazines_magazines', 'mm');

        $query->orderBy('mm.id', 'DESC');

        $record = $query->loadObject();

        return $record;
    }

    public function addUserMagazine() {

        $latest_magazine = $this->getLatestMagazine();
        $subscribers = $this->getSubscriberList();

        foreach ($subscribers as $subscriber) {

            $data = new \stdClass();
            $data->user_id = $subscriber->user_id;
            $data->magazine_id = $latest_magazine->id;

            $this->saveRecordByEntity('magazines_magazines_users', $data);

            $this->sendMagazineEmail($subscriber, $latest_magazine);
        }
    }

    public function sendMagazineEmail($user, $magazine) {

        $email = new Email();

        $attachments = array();
        $email_parameters = array();

        $email_parameters['user'] = $user;
        $email_parameters['magazine'] = $magazine;

        $attachments[] = JPATH_ROOT . $magazine->file_file;
        $email_address = (isset($email_parameters['user']->email)) ? $email_parameters['user']->email : '';

        $email->sendDefinedLayoutEmail('magazines.magazines.subscription', $email_address, $email_parameters);
    }

}
