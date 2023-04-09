<?php

namespace NS\ColorAdminBundle\Filter\Search;

use JsonSerializable;

interface Select2SearchResultInterface extends JsonSerializable
{
    /** @return string|int */
    public function getId();
    public function getText(): string;
    public function getExtra(): ?array;
}
