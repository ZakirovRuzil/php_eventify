<?php

declare(strict_types=1);

namespace app\migrations;

function getMigrations(): array {
    return [new Migration_0()];
}
