<?php


namespace flexycms\FlexyFilemanagerBundle\Controller;

use flexycms\BreadcrumbsBundle\Utils\Breadcrumbs;
use Symfony\Component\Routing\Annotation\Route;
use flexycms\FlexyAdminFrameBundle\Controller\AdminBaseController;

class FilemanagerController extends AdminBaseController
{
    /**
     * @Route("/admin/filemanager", name="admin_filemanager")
     */
    public function index()
    {
        $forRender = parent::renderDefault();

        $forRender['baseUrl'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http"). "://". @$_SERVER['HTTP_HOST'];

        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->prepend($this->generateUrl("admin_filemanager"), 'Файловый менеджер');
        $breadcrumbs->prepend($this->generateUrl("admin_home"), 'Главная');
        $forRender['breadcrumbs'] = $breadcrumbs;
        $forRender['title'] = "Файловый менеджер";

        return $this->render('@FlexyFilemanager/filemanager.html.twig', $forRender);
    }
}