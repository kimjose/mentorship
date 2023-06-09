<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model{

    protected $fillable = ['name', 'team_lead'];

    /**
     * @return User
     */
    public function lead(){
        return User::find($this->team_lead);
    }

    /**
     * @return Facility[]
     */
    public function facilities(){
        return Facility::where('team_id', $this->id)->get();
    }

}
