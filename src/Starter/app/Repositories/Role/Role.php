<?php

namespace {{App\}}Repositories\Role;

use {{App\}}Repositories\User\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'label'
    ];

    /**
     * Rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:roles',
        'label' => 'required'
    ];

    /**
     * A Roles users
     *
     * @return Relationship
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
