<?php

namespace App\Models\Universal;
use Paracha\Acl\Traits\CompanyUserHasRoleAndPermission;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
	use CompanyUserHasRoleAndPermission;
	protected $table = 'company_user';
	protected $connection = config('database.default');
	protected $fillable = ['company_id','user_id','settings','authorized'];

}
