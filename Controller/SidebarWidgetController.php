<?php

namespace RA\OroCrmTimeLapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SidebarWidgetController extends Controller
{
    /**
     * @Route("/widget/sidebar/{perPage}", name="timelap_sidebar_widget", defaults={"perPage" = 10})
     * @Template("RAOroCrmTimeLapBundle:Task/widget:tasksWidget.html.twig")
     *
     * @param integer $perPage
     * @return array
     */
    public function timeTrackingWidgetAction($perPage)
    {
        $id = $this->getUser()->getId();
        $perPage = (int)$perPage;
        $tasks = $this->get('timelap.orocrmtask.repository')->getTasksAssignedTo($id, $perPage);

        return ['tasks' => $tasks];
    }
}
