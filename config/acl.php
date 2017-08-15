<?php

return [
    /**
     * Array of all the paths in Tenant Migrations in Migrations Folder
     * App/Database/Migrations/Tenant
     */
    'tenantMigrations' => ['/tenants'],

    // Note: In case of exporting the Models manually, use your own Paths
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
     * How to Identify Tenant Company on Request
     * Tenant identification options: user, subdomain
     * 1) User: When 1 Company/Tenant belongs to One User Only
     * 2) Subdomain: When 1 User can belong to many tenants. E.g. One User have Multiple Companies for Accounting Software etc.
     */
    'tenantIdentification' => 'subdomain',

    'connections' =>
    [
    'master' => 'mysql',
    'tenant' => 'tenant'
    ]
    ];
