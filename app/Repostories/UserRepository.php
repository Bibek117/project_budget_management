<?php

namespace App\Repostories;

use App\Models\User;

class UserRepository extends CommonInterfaceRepository{

    public function __construct(User $user){
        parent::__construct($user);
    }
}
