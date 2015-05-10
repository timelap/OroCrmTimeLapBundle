<?php

namespace RA\OroCrmTimeLapBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

class TrackerController extends Controller
{
    /**
     * @Route(
     *        "/startRecord/task/{taskId}",
     *        name="timelap_start_record",
     *        requirements={"taskId"="\d+"}
     * )
     * @param $taskId
     * @return JsonResponse
     */
    public function startTimeTrackingRecordAction($taskId)
    {
        $user = $this->getCurrentUser();
        $task = $this->loadTask($taskId);
        $trackerService = $this->get('timelap.tracker.service');
        $timeSpentFactory = $this->get('timelap.time_spent.factory');

        $trackerService->startTracking($user, $task);
        $tracker = $trackerService->getTracker($user);

        $response = new JsonResponse();
        $response->setData(
            [
                'time' => $timeSpentFactory->create($tracker->getSpentSeconds())->getValue(),
                'taskId' => $tracker->getTask()->getId(),
                'taskSubject' => $tracker->getTask()->getSubject()
            ]
        );

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('timelap.timetracking.controller.started.message')
        );

        return $response;
    }

    /**
     * @Route(
     *        "/stopRecord",
     *        name="timelap_stop_record"
     * )
     * @return JsonResponse
     */
    public function stopTimeTrackingRecordAction()
    {
        $user = $this->getCurrentUser();
        $this->get('timelap.tracker.service')->stopTracking($user);

        $response = new JsonResponse();
        $response->setData(['success' => true]);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('timelap.timetracking.controller.stopped.message')
        );

        return $response;
    }

    /**
     * @Route(
     *        "/getRecord",
     *        name="timelap_get_record"
     * )
     * @return JsonResponse
     */
    public function getTimeTrackingRecordAction()
    {
        $user = $this->getCurrentUser();
        $tracker = $this->get('timelap.tracker.service')->getTracker($user);
        $timeSpentFactory = $this->get('timelap.time_spent.factory');

        $response = new JsonResponse();
        $response->setData(
            [
                'time' => $timeSpentFactory->create($tracker->getSpentSeconds())->getValue(),
                'taskId' => $tracker->getTask()->getId(),
                'taskSubject' => $tracker->getTask()->getSubject()
            ]
        );
        return $response;
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
