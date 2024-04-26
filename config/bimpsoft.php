<?php

return [
    'email'    => env('BIMPSOFT_EMAIL'),
    'password' => env('BIMPSOFT_PASSWORD'),
    'company'  => env('BIMPSOFT_COMPANY'),
    'project_uuid' => env('BIMPSOFT_PROJECT_UUID'),
    'manager_uuid' => env('BIMPSOFT_MANAGER_UUID'),
    'contract_uuid' => env('BIMPSOFT_CONTRACT_UUID'),
    'line_of_business_uuid' => env('BIMPSOFT_LINE_OF_BUSINESS_UUID'),
    'organization_uuid' => env('BIMPSOFT_ORGANIZATION_UUID'),
    'warehouse_uuid' => env('BIMPSOFT_WAREHOUSE_UUID'),
    'customer_uuid' => env('BIMPSOFT_CUSTOMER_UUID'),
    'status_uuid' => env('BIMPSOFT_STATUS_UUID'),
    'package_mails' => env('BIMPSOFT_PACKAGE_MAILS', 'info@zdorovoshop.com,buh@zdorovoshop.com'),
];
