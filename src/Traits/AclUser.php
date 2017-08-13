<?php

namespace Paracha\Acl\Traits;

use Paracha\Acl\Models\Permission;
use App\User;

trait AclUser
{
	public function companies(){
		return $this->BelongsToMany('App\Models\Universal\Company')->withPivot('settings', 'authorized');
	}
	
	public function ownedCompanies()
	{
		return $this->HasMany('App\Models\Universal\Company');
	}
}
