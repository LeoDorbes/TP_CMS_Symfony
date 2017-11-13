<?php

namespace HookBundle\Twig;

use HookBundle\Entity\Hook;
use HookBundle\Entity\HookModule;
use ModuleBundle\Entity\Module;
use TemplateBundle\Entity\Template;
use Twig_Extension;
use Twig_Environment;
use Twig_SimpleFunction;
use Doctrine\ORM\EntityManager;
use ModuleBundle\Service\ModuleManager;

class HookExtension extends Twig_Extension
{
    private $em;
    private $mm;

    public function __construct(EntityManager $em, ModuleManager $mm)
    {
        $this->em = $em;
        $this->mm = $mm;
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('hook', array($this, 'hookFunction'), array(
                "is_safe" => array("html"),     // Allow HTML to be returned and displayed
                'needs_environment' => true,    // Inject the Twig environment into the function
            )),
        );
    }

    /**
     * Hook from template
     * @param  Twig_Environment $env Twig service for rendering
     * @param  String $hook_name
     * @param  String $page_name
     * @return String                      The HTML to display
     */
    public function hookFunction(Twig_Environment $env, $hook_name, $page_name)
    {
        //       1. Load hook from DB
        $hook = $this->em->getRepository(Hook::class)->findOneBy(array('name' => $hook_name));
        //       2. Load modules ID registered with the hook
        $hookModules = $this->em->getRepository(HookModule::class)->findByIdModule($hook->getId());

        //       3. Load modules
        $modules = array();
        foreach ($hookModules as $moduleElement) {
            $mod = $this->em->getRepository(Module::class)->find($moduleElement->getIdModule());
            $modules[$mod->getName()] = [$mod->getName(), $mod];
        }

        $this->mm->load($modules);

        //       5. Render each module view

        $twig = "";
        foreach ($modules as $name => $mod) {
            if (!is_null($this->em->getRepository(Template::class)->findOneById($mod[1]->getId()))) {
                $twig .= $this->mm->getQuentainer()->get('template.manager')->getTemplateTwig($name);
            }
        }
        //       6. Return HTML
        return $twig;
    }

    public function getName()
    {
        return "hook_extension";
    }
}
