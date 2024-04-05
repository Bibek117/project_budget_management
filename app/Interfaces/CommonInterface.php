<?php

namespace App\Interfaces;

interface CommonInterface{

    public function getAll();
    public function getById(int $id);
    public function create(array $data);
    public function updateById(int $id,array $data);
    public function deleteById(int $id);

}
?>