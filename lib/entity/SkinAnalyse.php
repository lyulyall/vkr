<?php

namespace vdc\custom\entity;


use Bitrix\Main\Type\DateTime;


class SymptomAnalyse{
	public function __construct(
		protected ?int $id = null,
		protected array $image,
		protected string $predict = '',
		protected ?DateTime $dateTime = null,
	) { }
}