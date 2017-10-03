<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    protected $prenom;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $famille;

    /**
     * @return mixed
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * @param mixed $famille
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;
    }

    /**
     * @return mixed
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * @param mixed $race
     */
    public function setRace($race)
    {
        $this->race = $race;
    }
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $race;

    /**
     * @ORM\Column(type="string", length=255)
     */

    protected $nom;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $nourriture;

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getNourriture()
    {
        return $this->nourriture;
    }

    /**
     * @param mixed $nourriture
     */
    public function setNourriture($nourriture)
    {
        $this->nourriture = $nourriture;
    }
    /**
     * @ORM\Column(type="integer")
     */
    protected $age;



    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="friends",
     *     joinColumns={@ORM\JoinColumn(name="thisis", referencedColumnName="id")}, inverseJoinColumns={@ORM\JoinColumn(name="friendwith", referencedColumnName="id")}
     * )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $friends;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->friends = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return array
     */
    public function getFriendsList()
    {
        return $this->friends->toArray();
    }

    /**
     * @param  User $friend
     * @return void
     */
    public function addFriend(User $friend)
    {
        if (!$this->friends->contains($friend)) {
            $this->friends->add($friend);
            $friend->addFriend($this);
        }
    }

    /**
     * @param  User $friend
     * @return void
     */
    public function removeFriend(User $friend)
    {

        if ($this->friends->contains($friend)) {
            $this->friends->removeElement($friend);
            $friend->removeFriend($this);
        }
    }

}