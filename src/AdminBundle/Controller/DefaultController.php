<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\BrowserKit\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/asdasd")
     */
    public function indexAction(Request $request)
    {
        return $this->redirect($this->generateUrl('order_list'));
    }

}
