<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model{
    
    protected $fillable = ["access_level", "name", "description", "permissions", "created_by"];

    /**
     * @return UserPermission[]
     */
    public function getPermissions(){
        $p = explode(',', $this->permissions);
        return UserPermission::whereIn('id', $p)->get();
    }

}
