<?php

namespace App\Repostories;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;

class UserRepository extends CommonInterfaceRepository{

    public function __construct(User $user){
        parent::__construct($user);
    }

    public function assignContactTypeToUser($request){
        $assignedContatcIds = DB::table('contacts')->where('user_id', $request['user_id'])->pluck('contacttype_id')->toArray();

        $newIdsToAssign = $request['contacttype_id'];

        //arrar_diff returns array of difference in elements
        $IDsToAttach = array_diff($newIdsToAssign, $assignedContatcIds);
        $IDsToDettach = array_diff($assignedContatcIds, $newIdsToAssign);


        $data = [];
        foreach ($IDsToAttach as $contacttypeId) {
            $data[] = [
                'user_id' => $request['user_id'],
                'contacttype_id' => $contacttypeId,
            ];
        }
        Contact::insert($data);
        Contact::where('user_id', $request['user_id'])
            ->whereIn('contacttype_id', $IDsToDettach)
            ->delete();

    }


    public function assignProjectToUser(array $request)
    {
        $user = $this->getById($request['user_id']);
        $user->projects()->sync($request['project_id']);
    }

}
