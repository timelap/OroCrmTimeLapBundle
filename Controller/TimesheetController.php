<?php

namespace RA\OroCrmTimeLapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\UserBundle\Entity\User;

use RA\OroCrmTimeLapBundle\Calendar\CalendarPeriod;
use RA\OroCrmTimeLapBundle\Model\Timesheet;

class TimesheetController extends Controller
{
    /**
     * @Route("/currentMonthTimesheet/forCurrentUser", name="timelap_current_user_current_month_timesheet")
     * @Template("RAOroCrmTimeLapBundle:Timesheet:timesheet.html.twig")
     *
     * @return array
     */
    public function getCurrentMonthTimesheetForCurrentUserAction()
    {
        $service = $this->get('timelap.timesheet.service');
        $timesheet = $service->createCurrentMonthTimeSheetPerUser($this->getCurrentUser());

        $month = $this->getCalendarPeriodFromTimesheet($timesheet);

        return ['month' => $month, 'timesheet' => $timesheet, 'user_select_form' => $this->buildUserSelector()];
    }

    /**
     * @Route(
     *      "/monthTimesheet/forCurrentUser/{month}",
     *      name="timelap_current_user_month_timesheet",
     *      requirements={"month"="\d{4}-\d{2}"}
     * )
     *
     * @Template("RAOroCrmTimeLapBundle:Timesheet:timesheet.html.twig")
     *
     * @param string $month
     * @return array
     */
    public function getMonthTimesheetForCurrentUserAction($month)
    {
        $service = $this->get('timelap.timesheet.service');
        $timesheet = $service->createMonthTimeSheetPerUser($this->getCurrentUser(), $month);

        $month = $this->getCalendarPeriodFromTimesheet($timesheet);

        return ['month' => $month, 'timesheet' => $timesheet, 'user_select_form' => $this->buildUserSelector()];
    }

    /**
     * @Route(
     *        "/timesheet/month/{month}/user/{id}",
     *        name="timelap_user_timesheet",
     *        requirements={"id" = "\d+", "month"="\d{4}-\d{2}"},
     *        defaults={"id" = 0}
     * )
     * @Template("RAOroCrmTimeLapBundle:Timesheet:timesheet.html.twig")
     *
     * @param integer $id
     * @param string $month
     * @return array
     */
    public function timesheetForUserAction($id, $month)
    {
        $service = $this->get('timelap.timesheet.service');
        $user = $this->get('oro_user.manager')->findUserBy(['id' => $id]);

        if (null === $user) {
            throw new \RuntimeException('User does not exist.');
        }

        $timesheet = $service->createMonthTimeSheetPerUser($user, $month);
        $month = $this->getCalendarPeriodFromTimesheet($timesheet);

        return ['month' => $month, 'timesheet' => $timesheet, 'user_select_form' => $this->buildUserSelector()];
    }

    /**
     * @param Timesheet $timesheet
     * @return CalendarPeriod
     */
    private function getCalendarPeriodFromTimesheet(Timesheet $timesheet)
    {
        $calendarService = $this->get('timelap.calendar.service');
        return $calendarService->getCalendarPeriod($timesheet->getPeriod());
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

    /**
     * @return \Symfony\Component\Form\FormView
     */
    private function buildUserSelector()
    {
        return
        $this->get('form.factory')
            ->createNamed(
                'timesheet_user',
                'oro_user_select',
                null,
                [
                    'autocomplete_alias' => 'acl_users',
                    'configs' => [
                        'entity_id'               => $this->getCurrentUser()->getId(),
                        'entity_name'             => 'OroUserBundle:User',
                        'excludeCurrent'          => false,
                        'extra_config'            => 'acl_user_autocomplete',
                        'permission'              => 'VIEW',
                        'placeholder'             => 'Select a user',
                        'result_template_twig'    => 'OroUserBundle:User:Autocomplete/result.html.twig',
                        'selection_template_twig' => 'OroUserBundle:User:Autocomplete/selection.html.twig'
                    ],
                    'grid_name' => 'users-select-grid-exclude-owner',
                    'random_id' => false,
                    'required'  => true
                ]
            )
            ->createView();
    }
}
