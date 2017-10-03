<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User as Users;

class DefaultController extends Controller
{
    /**
     * @Route("/addfriend" ,  name="addfriend")
     */
    public function addfriendAction(Request $request)
    {
        $datas = (Object) $request->getContent();
        $data2 =  $datas->scalar;
        $data = json_decode($data2, true);

        if($data['user'] != null && $data['id']) {
            $value = $data['user'];
            $id = $data['id'];
            $em = $this->getDoctrine()->getManager();
            $me = $this->getDoctrine()->getRepository("AppBundle:User")->find($id);
            $user2 = $this->getDoctrine()->getRepository("AppBundle:User")->find($value);
            $me->addFriend($user2);
            $em->persist($me);
            $em->flush();
            $responsevalue = 'ok';
        }
        else
        {
            $responsevalue = 'no';
        }
        return $this->sendRequest($responsevalue);


    }

    /**
     * @Route("/removefriend" ,  name="removefriend")
     */
    public function removefriendAction(Request $request)
    {
        $datas = (Object) $request->getContent();
        $data2 =  $datas->scalar;
        $data = json_decode($data2, true);

        if($data['user'] != null && $data['id']) {
            $value = $data['user'];
            $id = $data['id'];
            $em = $this->getDoctrine()->getManager();
            $me = $this->getDoctrine()->getRepository("AppBundle:User")->find($id);
            $user2 = $this->getDoctrine()->getRepository("AppBundle:User")->find($value);
            $me->removeFriend($user2);
            $em->persist($me);
            $em->flush();
            $responsevalue = 'ok';
        }
        else
        {
            $responsevalue = 'no';
        }
        return $this->sendRequest($responsevalue);

    }


    /**
     * @Route("/changerprofil" ,  name="changerprofil")
     */
    public function changerprofilAction(Request $request)
    {
        $datas = (Object) $request->getContent();
        $data2 =  $datas->scalar;
        $data = json_decode($data2, true);

        if($data['user'] != null) {
            $em = $this->getDoctrine()->getManager();
            $meindatabase = $this->getDoctrine()->getRepository("AppBundle:User")->find($data['user']);
            $meindatabase->setNom($data['nom']);
            $meindatabase->setPrenom($data['prenom']);
            $meindatabase->setAge($data['age']);
            $meindatabase->setRace($data['race']);
            $meindatabase->setFamille($data['famille']);
            $meindatabase->setNourriture($data['nourriture']);
            $em->persist($meindatabase);
            $em->flush();
            $responsevalue = 'ok';
        }
        else
        {
            $responsevalue = 'no';
        }
        return $this->sendRequest($responsevalue);
    }
    /**
     * @Route("/checking" ,  name="cheking")
     */
    public function chekingAction(Request $request)
    {
        $datas = (Object) $request->getContent();
        $data2 =  $datas->scalar;
        $data = json_decode($data2, true);
        $username = $data['username'];
        $password = $data['password'];
        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
        $responss = 'false';
        $user = $user_manager->findUserByUsername($username);
        if(!is_null($user))
        {
            $encoder = $factory->getEncoder($user);
            $salt = $user->getSalt();

            if($encoder->isPasswordValid($user->getPassword(), $password, $salt))
            {

                $table = array();
                $table['id'] = $user->getId();
                $table['username'] = $user->getUsername();
                $table['nom'] = $user->getNom();
                $table['prenom'] = $user->getPrenom();
                $table['race'] = $user->getRace();
                $table['age'] = $user->getAge();
                $table['famille'] = $user->getFamille();
                $table['nourriture'] = $user->getNourriture();
                $responsevalue = json_encode($table);
            }

        }
        else
        {
            $responsevalue = 'false';
        }

        return $this->sendRequest($responsevalue);

    }

    /**
     * @Route("/getfriends" ,  name="getfriends")
     */
    public function getfriendsAction(Request $request)
    {
        $datas = (Object) $request->getContent();
        $data2 =  $datas->scalar;
        $data = json_decode($data2, true);
        if($data['user'] != null ) {
            $me = $this->getDoctrine()->getRepository("AppBundle:User")->find($data['user']);
            $serializer =  $this->container->get('jms_serializer')->serialize($me, 'json');
            $responsevalue = $serializer;
        }
        else
        {
            $responsevalue = 'no';
        }
        return $this->sendRequest($responsevalue);

    }
    /**
     * @Route("/getnewfriends" ,  name="getnewfriends")
     */
    public function getnewfriendsAction(Request $request)
    {
        $datas = (Object) $request->getContent();
        $data2 =  $datas->scalar;
        $data = json_decode($data2, true);
        if($data['user'] != null ) {
            $me = $this->getDoctrine()->getRepository("AppBundle:User")->find($data['user']);
            $query = $this->getDoctrine()->getRepository("AppBundle:User")->createQueryBuilder('u');
            $query->where('u.id != :iduser')->setParameter('iduser',$data['user']);
            $allusers = $query->getQuery()->getResult();
            $myfriends = $me->getFriendsList();
            $newfriends = array_diff($allusers, $myfriends);
            $responsevalue =  $this->container->get('jms_serializer')->serialize($newfriends, 'json');
        }
        else
        {
            $responsevalue = 'no';
        }
        return $this->sendRequest($responsevalue);
    }

    private function sendRequest($request)
    {
        $response = new Response($request);
        $responseHeaders = $response->headers;
        $responseHeaders->set('Content-Type', 'application/json');
        $responseHeaders->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        $responseHeaders->set('Access-Control-Allow-Origin', '*');

        $responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');

        return $response;
    }
}




