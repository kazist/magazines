<?php

namespace Magazines\Magazines\Users\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="magazines_magazines_users", indexes={@ORM\Index(name="user_id_index", columns={"user_id"}), @ORM\Index(name="magazine_id_index", columns={"magazine_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Users extends \Kazist\Table\BaseTable {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string", length=255, nullable=false)
     */
    protected $user_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="magazine_id", type="integer", length=11, nullable=false)
     */
    protected $magazine_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=false)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=false)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=false)
     */
    protected $date_modified;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set user_id
     *
     * @param string $userId
     * @return Users
     */
    public function setUserId($userId) {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return string 
     */
    public function getUserId() {
        return $this->user_id;
    }

    /**
     * Set magazine_id
     *
     * @param integer $magazineId
     * @return Users
     */
    public function setMagazineId($magazineId) {
        $this->magazine_id = $magazineId;

        return $this;
    }

    /**
     * Get magazine_id
     *
     * @return integer 
     */
    public function getMagazineId() {
        return $this->magazine_id;
    }

    /**
     * Get created_by
     *
     * @return integer 
     */
    public function getCreatedBy() {
        return $this->created_by;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated() {
        return $this->date_created;
    }

    /**
     * Get modified_by
     *
     * @return integer 
     */
    public function getModifiedBy() {
        return $this->modified_by;
    }

    /**
     * Get date_modified
     *
     * @return \DateTime 
     */
    public function getDateModified() {
        return $this->date_modified;
    }

}
