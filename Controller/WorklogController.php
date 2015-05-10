<?php

namespace RA\OroCrmTimeLapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Form\WorklogInput;
use RA\OroCrmTimeLapBundle\Form\WorklogType;

class WorklogController extends Controller
{
    /**
     * @Route("/createWorklog/task/{taskId}", name="timelap_worklog_create", requirements={"taskId"="\d+"})
     * @Template("RAOroCrmTimeLapBundle:Worklog:update.html.twig")
     *
     * @param integer $taskId
     * @return array
     */
    public function createWorklogAction($taskId)
    {
        $worklogInput = new WorklogInput();
        $worklogInput->setTaskId($taskId);
        return ['form' => $this->prepareForm($worklogInput)->createView()];
    }

    /**
     * @Route("/editWorklog/{worklogId}", name="timelap_worklog_edit", requirements={"worklogId"="\d+"})
     * @Template("RAOroCrmTimeLapBundle:Worklog:update.html.twig")
     *
     * @param integer $worklogId
     * @return array
     */
    public function editWorklogAction($worklogId)
    {
        $worklog = $this->get('timelap.worklog.service')->getWorklog($worklogId);
        $worklogInput = new WorklogInput();
        $worklogInput->setId($worklog->getId());
        $worklogInput->setTimeSpent((string) $worklog->getTimeSpent());
        $worklogInput->setDateStarted($worklog->getDateStarted());
        $worklogInput->setDescription($worklog->getDescription());
        $worklogInput->setTaskId($worklog->getTask()->getId());
        return ['form' => $this->prepareForm($worklogInput)->createView()];
    }

    /**
     * @param WorklogInput $worklogInput
     * @return \Symfony\Component\Form\Form
     */
    private function prepareForm(WorklogInput $worklogInput)
    {
        $form = $this->createForm(new WorklogType(), $worklogInput);
        return $form;
    }

    /**
     * @Route(
     *        "/saveWorklog/{worklogId}",
     *        name="timelap_worklog_save",
     *        requirements={"worklogId"="\d+"}, defaults={"worklogId"="0"}
     * )
     * @Template("RAOroCrmTimeLapBundle:Worklog:update.html.twig")

     * @param integer $worklogId
     * @return array|RedirectResponse
     */
    public function saveWorklogAction($worklogId)
    {
        $redirect = ($this->getRequest()->get('no_redirect')) ? false : true;
        $form = $this->createForm(new WorklogType(), new WorklogInput());
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            /** @var WorklogInput $input */
            $input = $form->getData();
            if ($worklogId) {
                $this->get('timelap.worklog.service')->updateWorklog(
                    $worklogId,
                    $input->getTimeSpent(),
                    $input->getDateStarted(),
                    $input->getDescription()
                );
            } else {
                $task = $this->loadTask($input->getTaskId());
                $user = $this->getCurrentUser();
                $this->get('timelap.worklog.service')
                    ->logWork(
                        $input->getTimeSpent(),
                        $input->getDateStarted(),
                        $task,
                        $user,
                        $input->getDescription()
                    );
            }
            if ($redirect) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('timelap.worklog.controller.saved.message')
                );

                return $this->get('oro_ui.router')->redirectAfterSave(
                    ['route' => 'timelap_worklog_create', 'parameters' => ['taskId' => $input->getTaskId()]],
                    ['route' => 'orocrm_task_view', 'parameters' => ['id' => $input->getTaskId()]]
                );
            }
        }

        $saved = true;

        return [
            'saved' => $saved,
            'form'  => $form->createView()
        ];
    }

    /**
     * @Route(
     *        "/deleteWorklog/task/{taskId}/worklog/{worklogId}",
     *        name="timelap_worklog_delete",
     *        requirements={"taskId"="\d+", "worklogId"="\d+"}
     * )
     *
     * @param integer $taskId
     * @param integer $worklogId
     * @return RedirectResponse
     */
    public function deleteWorklog($taskId, $worklogId)
    {
        $this->get('timelap.worklog.service')->deleteWorklog($worklogId);
        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('timelap.worklog.controller.deleted.message')
        );
        return $this->redirect($this->generateUrl('orocrm_task_view', ['id' => $taskId]));
    }

    /**
     * @param int $taskId
     * @return Task
     * @throws \RuntimeException if Task can't be found.
     */
    private function loadTask($taskId)
    {
        $task = $this->get('timelap.orocrmtask.repository')->find((int) $taskId);
        if (null === $task) {
            throw new \RuntimeException('Task can\'t be found.');
        }

        return $task;
    }

    /**
     * @return null|User
     */
    private function getCurrentUser()
    {
        $token = $this->container->get('security.context')->getToken();
        if (!$token) {
            throw new \RuntimeException('Current user is unknown.');
        }
        return $token->getUser();
    }
}
