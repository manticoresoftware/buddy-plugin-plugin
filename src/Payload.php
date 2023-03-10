<?php declare(strict_types=1);

/*
  Copyright (c) 2023, Manticore Software LTD (https://manticoresearch.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License version 2 or any later
  version. You should have received a copy of the GPL license along with this
  program; if you did not, you can find it at http://www.gnu.org/
*/
namespace Manticoresearch\Buddy\Plugin\CreatePlugin;

use Manticoresearch\Buddy\Core\Error\QueryParseError;
use Manticoresearch\Buddy\Core\Network\Request;
use Manticoresearch\Buddy\Core\Plugin\BasePayload;

final class Payload extends BasePayload {
	public string $path;

	public function __construct(public string $package, public ?string $version = null) {
	}

  /**
	 * @param Request $request
	 * @return static
	 */
	public static function fromRequest(Request $request): static {
		$regex = "/^CREATE PLUGIN (\S+) TYPE 'buddy'( VERSION '(\S+)')?$/ius";

		if (!preg_match($regex, $request->payload, $matches)) {
			throw new QueryParseError('Failed to parse query');
		}

		$package = $matches[1];
		$version = $matches[3] ?? null;

		$self = new static($package, $version);
		$self->path = $request->path;
		return $self;
	}

	/**
	 * @param Request $request
	 * @return bool
	 */
	public static function hasMatch(Request $request): bool {
		return stripos($request->payload, 'create plugin') === 0;
	}
}
