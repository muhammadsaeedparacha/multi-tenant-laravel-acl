<?php

namespace App\Models\Universal;
use Paracha\Acl\Traits\AclCompanyUser;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
	use AclCompanyUser;
	protected $table = 'company_user';
	protected $fillable = ['company_id','user_id','settings','authorized'];

}
