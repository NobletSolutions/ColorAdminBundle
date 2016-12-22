<?php

namespace Tests\NS\ColorAdminBundle;

/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 22/12/16
 * Time: 12:30 PM
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function createMock($originalClassName)
    {
        if (method_exists(parent::class, 'createMock')) {
            return parent::createMock($originalClassName);
        }

        $obj = $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning();

        if (method_exists($obj, 'disallowMockingUnknownTypes')) {
            $obj->disallowMockingUnknownTypes();
        }

        return $obj->getMock();
    }
}
