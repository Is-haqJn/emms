<?php
namespace App\Trait;

trait PermissionModule {
  public function module(){
    $index['permission_array'] = [
      'View Users',
      'Create Users',
      'Edit Users',
      'Delete Users',
      'View Roles',
      'Create Roles',
      'Edit Roles',
      'Delete Roles',
      'View Permissions',
      'Create Permissions',
      'Edit Permissions',
      'Delete Permissions',
      'View Hospitals',
      'Create Hospitals',
      'Edit Hospitals',
      'Delete Hospitals',
      'View Equipments',
      'Create Equipments',
      'Edit Equipments',
      'Delete Equipments',
      'View Breakdown Maintenance',
      'Create Breakdown Maintenance',
      'Edit Breakdown Maintenance',
      'Delete Breakdown Maintenance',
      'Create Preventive Maintenance',
      'View Preventive Maintenance',
      'Edit Preventive Maintenance',
      'Delete Preventive Maintenance',
      'View Calibrations',
      'Create Calibrations',
      'Edit Calibrations',
      'Delete Calibrations',
      'View Time Indicator Report',
      'View Maintenance Cost',
      'Create Maintenance Cost',
      'Edit Maintenance Cost',
      'Delete Maintenance Cost',
      'View Departments',
      'Create Departments',
      'Edit Departments',
      'Delete Departments',
      'View QR Generate',
      'View QR Scan',
      'Create QR',
      'View Equipments Report',
      'View Calibration Stickers',
      'View Preventive Reminder',
			'View Calibration Reminder',

    ];
    $index['module_names'] = array_unique(array_map(function ($permission) {
      // Extracting the module name by removing the action part
      return preg_replace('/(View|Create|Edit|Delete)\s/', '', $permission);
    }, $index['permission_array']));
    return 
    [
      'module_names'=>$index['module_names'],
      'permission_array'=>$index['permission_array']
    ];
  }
}