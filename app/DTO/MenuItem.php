<?php

namespace App\DTO;

readonly class MenuItem {
    public int $id;
    public string $name;
    public int $price;

    public function __construct(int $id, string $name, int $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function toJson() {
        return json_encode([
            'idshka' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
        ]);
    }
}