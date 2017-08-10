<?php

return [
    /**
     * Role class used for ACL.
     */
    'role'       => \Yajra\Acl\Models\Role::class,

    /**
     * Permission class used for ACL.
     */
    'permission' => \Yajra\Acl\Models\Permission::class,

    /**
     * User class used for ACL.
     */
    'user' => App\User::class,

    /**
     * Pivot Table of Companies and Users for ACL
     */
    'companyUser' => App\Models\Universal\CompanyUser::class,
    ];
