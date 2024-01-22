<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

    protected $fillable = ['program_ids', 'category_id', 'first_name', 'middle_name', 'last_name', 'email', 'phone_number', 'password', 'last_login', 'facility_id', 'active', 'created_by', 'program_id'];

    protected $hidden = ['password'];

    public function getNames(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getCategory(){
        return UserCategory::find($this->category_id);
    }

    /**
     * @return Program[]
     */
    public function getPrograms(){
        return Program::whereIn('id', explode(',', $this->program_ids))->get();
    }

    /**
     * @return array
     */
    public function getProgramsNames(){
        return Program::whereIn('id', explode(',', $this->program_ids))->get(['name'])->pluck('name')->toArray();
    }
}
