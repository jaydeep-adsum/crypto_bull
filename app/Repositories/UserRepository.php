<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'full_name',
        'user_name',
        'email',
        'password',
        'tether_account',
        'secret_question',
        'secret_answer',
        'term_condition',
    ];
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return User::class;
    }
}
