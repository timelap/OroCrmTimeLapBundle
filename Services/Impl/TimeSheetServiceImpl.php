<?php

namespace RA\OroCrmTimeLapBundle\Services\Impl;

use Oro\Bundle\LocaleBundle\Model\LocaleSettings;
use Oro\Bundle\UserBundle\Entity\User;

use RA\OroCrmTimeLapBundle\Model\PeriodFactory;
use RA\OroCrmTimeLapBundle\Model\Timesheet;
use RA\OroCrmTimeLapBundle\Model\Period;
use RA\OroCrmTimeLapBundle\Model\Timesheet\TaskList;
use RA\OroCrmTimeLapBundle\Model\WorklogRepository;
use RA\OroCrmTimeLapBundle\Services\TimeSheetService;

class TimeSheetServiceImpl implements TimeSheetService
{
    /**
     * @var WorklogRepository
     */
    private $workLogRepository;

    /**
     * @var PeriodFactory
     */
    private $periodFactory;

    /**
     * @var LocaleSettings
     */
    private $localeSettings;

    /**
     * @param WorklogRepository $workLogRepository
     * @param PeriodFactory     $periodFactory
     * @param LocaleSettings    $localeSettings
     */
    public function __construct(
        WorklogRepository $workLogRepository,
        PeriodFactory $periodFactory,
        LocaleSettings $localeSettings
    ) {
        $this->workLogRepository = $workLogRepository;
        $this->periodFactory     = $periodFactory;
        $this->localeSettings    = $localeSettings;
    }

    /**
     * {@inheritdoc}
     */
    public function createCurrentMonthTimeSheetPerUser(User $user)
    {
        $period = $this->periodFactory->currentMonthPeriod();
        $timesheet = $this->timesheet($user, $period);
        return $timesheet;
    }

    /**
     * {@inheritdoc}
     */
    public function createMonthTimeSheetPerUser(User $user, $date)
    {
        $period = $this->periodFactory->monthPeriod($date);
        $timeSheet = $this->timesheet($user, $period);
        return $timeSheet;
    }

    /**
     * @param User $user
     * @param Period $period
     * @return Timesheet
     */
    private function timesheet(User $user, Period $period)
    {
        $worklogs = $this->workLogRepository->listAllByUserAndPeriod($user, $period);
        $taskList = new TaskList($period, $worklogs, new \DateTimeZone($this->localeSettings->getTimeZone()));
        $timeSheet = new Timesheet($user, $taskList);
        return $timeSheet;
    }
}
