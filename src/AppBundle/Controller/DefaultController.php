<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of DefaultController
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class DefaultController extends Controller
{
    /**
     * @Route(name="index",path="/")
     * @Template()
     */
    public function indexAction()
    {
    }

    /**
     * @Route(name="account",path="/account")
     * @Template()
     */
    public function accountAction()
    {

    }
}