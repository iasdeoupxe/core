<?php
/**
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @copyright Copyright (c) 2022, ownCloud GmbH
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OC\Core\Command\Background\Queue;

use OCP\BackgroundJob\IJob;
use OCP\BackgroundJob\IJobList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Status extends Command {
	/** @var \OCP\BackgroundJob\IJobList */
	private $jobList;

	public function __construct(IJobList $jobList) {
		$this->jobList = $jobList;
		parent::__construct();
	}

	protected function configure() {
		$this
			->setName('background:queue:status')
			->setDescription('List queue status')
			->addOption('display-only-invalid-jobs', null, InputOption::VALUE_NONE, 'Only display jobs that are no longer valid');
	}

	private function getJobArgumentAsString($argument) {
		if (\is_array($argument)) {
			$argument = \json_encode($argument);
		}
		return $argument;
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int {
		$t = new Table($output);
		$t->setHeaders(['Job ID', 'Job', 'Job Arguments', 'Last Run', 'Last Checked', 'Reserved At', 'Execution Duration (s)']);
		$this->jobList->listJobs(
			function (IJob $job, string $classAsString) use ($t) {
				$t->addRow([
					$job->getId(),
					$classAsString,
					$this->getJobArgumentAsString($job->getArgument()),
					$job->getLastRun() == 0 ? 'N/A' : \date('c', $job->getLastRun()),
					\date('c', $job->getLastChecked()),
					$job->getReservedAt() == 0 ? 'N/A' : \date('c', $job->getReservedAt()),
					$job->getExecutionDuration() == -1 ? 'N/A' : $job->getExecutionDuration(),
				]);
			},
			$input->getOption('display-only-invalid-jobs')
		);
		$t->render();
		return 0;
	}
}
