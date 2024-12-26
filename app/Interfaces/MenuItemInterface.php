<?php

namespace App\Interfaces;

use App\DTO\MenuItem;

interface MenuItemInterface
{
    public function toMenuItem(): MenuItem;
}