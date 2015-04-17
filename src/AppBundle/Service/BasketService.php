<?php

namespace appBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class basket{
    private $em;

    private $session;

    protected $basket;

    public function __construct(EntityManager $em, Session $session){
        $this->em = $em;
        $this->session = $session;
        $this->basket = $this->session->get('basket');
    }

    public function addItem( $id, \DateTime $month ){
        $banner = $this->em->getRepository('AppBundle:Banner')->findOneById($id);
        if ($banner != null){
            $month = $banner->getMonths($month);
            if ($month != null){
                if ( $month->getStatus() == true ){
                    if ( isset( $this->basket[$banner->getId()][$month->format('m.Y')] ) ){
                        return 'Баннер на '.$month->format('m.Y').' уже выбран';
                    }else{
                        $this->basket[$banner->getId()][$month->format('m.Y')] = $month->getprice();
                        $this->save();
                    }
                }else{
                    return 'Баннер на '.$month->format('m.Y').' уже занят';
                }
            }else{
                return 'Данных о баннере за '.$month->format('m.Y').' не найдено';
            }
        }else{
            return 'Баннер не найден';
        }
        return false;
    }

    public function removeItem($id, \DateTime $month = null){
        if ( isset( $this->basket[$id] ) ){
            if ($month != null){
                unset($this->basket[$id][$month->format('m.Y')]);
            }else{
                unset($this->basket[$id]);
            }
            $this->save();
        }else{
            return 'Баннер не найден в корзине';
        }
        return false;
    }


    public function getItem($id){
        if ( isset( $this->basket[$id] ) ){
            return $this->basket[$id];
        }else{
            return 'Баннер не найден в корзине';
        }
    }

    public function clear(){
        unset($this->basket);
        $this->save();
    }

    protected function save(){
        $this->session->set('basket',$this->basket);
        $this->session->save();
    }

    protected function get(){
        $this->basket = $this->session->get('basket');
    }

    protected function getItems(){
        return $this->basket;
    }
}
