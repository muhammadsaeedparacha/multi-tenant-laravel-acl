<?php

return [
    /**
     * Role class used for ACL.
     */
    'role'       => \Paracha\Acl\Models\Role::class,

    /**
     * Permission class used for ACL.
     */
    'permission' => \Paracha\Acl\Models\Permission::class,

    /**
     * User class used for ACL.
     */
    'user' => App\User::class,

    /**
     * Pivot Table of Companies and Users for ACL
     */
    'companyUser' => App\Models\Universal\CompanyUser::class,
    ];
