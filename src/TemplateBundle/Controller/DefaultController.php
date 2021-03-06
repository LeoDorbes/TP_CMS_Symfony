<?php

namespace TemplateBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Assetic\AssetManager;
use Assetic\AssetWriter;
use Assetic\Factory\AssetFactory;
use Assetic\Factory\LazyAssetManager;
use Assetic\Filter\CssRewriteFilter;
use Assetic\FilterManager;

class DefaultController extends Controller
{
    /**
     * @Route("/front/stylesheets", name="front_template_stylesheets")
     * @Route("/admin/stylesheets", name="admin_template_stylesheets")
     */
    public function stylesheetsAction()
    {
        $am = $this->get('template.asset_manager');
        $fileArray = $am->getStylesheets();
        $grossfile = null;
        foreach ($fileArray as $file){
            $grossfile .= $file->getContents();
        }
        return new Response($grossfile);
    }

    /**
     * @Route("/front/javascripts", name="front_template_javascripts")
     * @Route("/admin/javascripts", name="admin_template_javascripts")
     */
    public function javascriptsAction()
    {
        $am = $this->get('template.asset_manager');
        $fileArray = $am->getJavascripts();
        $grossfile = null;
        foreach ($fileArray as $file){
            $grossfile .= $file->getContents();
        }
        return new Response($grossfile);
    }

    /**
     * @Route("/{path}", requirements={"path"=".+"})
     */
    public function filenameAction($path)
    {
        $exploded_path = explode(".", $path);
        $extension = end($exploded_path);

        if ($extension) {
            $path = $this->get('template.manager')->getAbsoluteTemplatePath() . "/" . $path;

            if (!file_exists($path))
                throw new NotFoundHttpException("Resource not found!");

            return new BinaryFileResponse($path);
        }

        return new Response();
    }
}
