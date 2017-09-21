<?php

namespace Paracha\Acl\Traits;

use Paracha\Acl\Models\Permission;
use App\User;

trait AclUser
{
	public function companies(){
		return $this->BelongsToMany(config('acl.company','Paracha\Acl\Models\Company'))->withPivot('settings', 'authorized');
	}
	
	public function ownedCompanies()
	{
		return $this->HasMany(config('acl.company','Paracha\Acl\Models\Company'));
	}
}
