<?php

namespace App;



class Role extends \Spatie\Permission\Models\Role {
    protected $guard_name = 'web';
}
