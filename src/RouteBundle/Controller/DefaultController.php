<?php

namespace RouteBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="front_home")
     * @Route("/category/{slug}", name="front_category")
     * @Route("/category/{category}/article/{slug}", name="front_article")
     * @Route("/search", name="front_search")
     * @Route("/login", name="front_login")
     */
    public function frontAction($slug = null, $category = null)
    {
        $params = array();
        if(!is_null($slug))
            $params['slug'] = $slug;
        if(!is_null($category))
            $params['category'] = $category;
        return new Response($this->render("::front.html.twig", $params));
    }

    /**
     * @Route("/admin", name="admin_home")
     * @Route("/admin/settings/{options}", name="admin_settings")
     * @Route("/admin/modules/{options}", name="admin_modules")
     * @Route("/admin/content/{options}", name="admin_content")
     * @return
     */
    public function adminAction($options = null)
    {
        return new Response($this->render("::admin.html.twig",
            array(
                'options' => $options,
            )));
    }
}
