<?php
/**
 * @author Robin McCorkell <robin@mccorkell.me.uk>
 * @author Vincent Petry <pvince81@owncloud.com>
 *
 * @copyright Copyright (c) 2018, ownCloud GmbH
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

namespace OCA\Files_External\Lib\Backend;

use OCA\Files_External\Lib\Auth\AmazonS3\AccessKey;
use OCA\Files_External\Lib\LegacyDependencyCheckPolyfill;
use OCP\Files\External\Backend\Backend as ExternalBackend;
use OCP\Files\External\DefinitionParameter;
use OCP\IL10N;

class AmazonS3 extends ExternalBackend {

	use LegacyDependencyCheckPolyfill;

	public function __construct(IL10N $l) {
		$this
			->setIdentifier('amazons3')
			->addIdentifierAlias('\OC\Files\Storage\AmazonS3') // legacy compat
			->setStorageClass('\OCA\Files_External\Lib\Storage\AmazonS3')
			->setText($l->t('Amazon S3'))
			->addParameters([
				(new DefinitionParameter('bucket', $l->t('Bucket'))),
				(new DefinitionParameter('hostname', $l->t('Hostname')))
					->setFlag(DefinitionParameter::FLAG_OPTIONAL),
				(new DefinitionParameter('port', $l->t('Port')))
					->setFlag(DefinitionParameter::FLAG_OPTIONAL),
				(new DefinitionParameter('region', $l->t('Region')))
					->setFlag(DefinitionParameter::FLAG_OPTIONAL),
				(new DefinitionParameter('use_ssl', $l->t('Enable SSL')))
					->setType(DefinitionParameter::VALUE_BOOLEAN),
				(new DefinitionParameter('use_path_style', $l->t('Enable Path Style')))
					->setType(DefinitionParameter::VALUE_BOOLEAN),
			])
			->addAuthScheme(AccessKey::SCHEME_AMAZONS3_ACCESSKEY);
		;
	}

}
