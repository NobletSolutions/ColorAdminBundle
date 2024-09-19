<?php

namespace NS\ColorAdminBundle\Filter\Search;

use JsonSerializable;

interface Select2SearchResultInterface extends JsonSerializable
{
    public function getId(): string|int;
    public function getText(): string;
    public function getExtra(): ?array;
}
