<?php
namespace Visol\EasyvoteEducation\Service;
use Visol\EasyvoteEducation\Domain\Model\Panel;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

class PanelService implements \TYPO3\CMS\Core\SingletonInterface  {

	/**
	 * @var \Visol\Easyvote\Domain\Repository\KantonRepository
	 * @inject
	 */
	protected $kantonRepository = NULL;

	/**
	 * @var \Visol\EasyvoteEducation\Domain\Repository\PanelRepository
	 * @inject
	 */
	protected $panelRepository = NULL;

	/**
	 * @param Panel $panel
	 * @return boolean
	 */
	public function isPanelInvitationAllowedForPanel(Panel $panel) {
		$cityOfPanel = $panel->getCity();
		if ($cityOfPanel instanceof \Visol\Easyvote\Domain\Model\City) {
			// panelLimit is either NULL or an integer
			$panelLimitForKanton = $cityOfPanel->getKanton()->getPanelLimit();
			if (is_integer($panelLimitForKanton)) {
				$existingPanelsInKanton = $this->panelRepository->countPanelsWithInvitationsByKanton($panel->getCity()->getKanton());
				if ($existingPanelsInKanton >= $panelLimitForKanton) {
					return FALSE;
				}
			}
		}
		// return false if all panels are reached or existing panels don't have a city set
		return TRUE;
	}

}