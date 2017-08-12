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
     * Tenants are called Companies
     */
    'company' => \Paracha\Acl\Models\Company::class,

    /**
     * User class used for ACL.
     */
    'user' => App\User::class,

    /**
     * Pivot Table of Companies and Users for ACL. Model for pivot is necessary for implementing
     * Advanced Features of Acess Control
     */
    'companyUser' => \Paracha\Acl\Models\CompanyUser::class,

    /**
     * User class used for ACL. Tenat identification options: subdomain
     */
    'tenantIdentification' => 'subdomain'
    ];
