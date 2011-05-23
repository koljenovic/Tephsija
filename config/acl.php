<?php

class Config_Acl
{

    public $list = array(
                          'Admin',
                          'Member',
                          'Public',
                          'Locked'
                          );

    public function aclPublic()
    {
        return true;
    }

    public function aclLocked()
    {
        return false;
    }

    public function aclMember()
    {
        $member = new Gear_Acl_Member;
        return $member->belongs();
    }
    
    public function aclAdmin()
    {
        $member = new Gear_Acl_Admin;
        return $member->belongs();
    }

}