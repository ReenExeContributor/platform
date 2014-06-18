<?php

namespace Oro\Bundle\IntegrationBundle\Tests\Unit\Entity;

use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\IntegrationBundle\Model\IntegrationSettings;

class ChannelTest extends \PHPUnit_Framework_TestCase
{
    const TEST_STRING  = 'testString';
    const TEST_BOOLEAN = true;

    /** @var array */
    protected static $testConnectors = ['customer', 'product'];

    /** @var Channel */
    protected $entity;

    protected function setUp()
    {
        $this->entity = new Channel();
    }

    protected function tearDown()
    {
        unset($this->entity);
    }

    /**
     * @dataProvider  getSetDataProvider
     *
     * @param string $property
     * @param mixed  $value
     * @param mixed  $expected
     */
    public function testSetGet($property, $value = null, $expected = null)
    {
        if ($value !== null) {
            call_user_func_array([$this->entity, 'set' . ucfirst($property)], [$value]);
        }

        $this->assertEquals($expected, call_user_func_array([$this->entity, 'get' . ucfirst($property)], []));
    }

    /**
     * @return array
     */
    public function getSetDataProvider()
    {
        $user = $this->getMock('Oro\Bundle\UserBundle\Entity\User');

        return [
            'id'                  => ['id'],
            'name'                => ['name', self::TEST_STRING, self::TEST_STRING],
            'type'                => ['type', self::TEST_STRING, self::TEST_STRING],
            'connectors'          => ['connectors', self::$testConnectors, self::$testConnectors],
            'syncPriority'        => ['syncPriority', self::TEST_STRING, self::TEST_STRING],
            'isTwoWaySyncEnabled' => ['isTwoWaySyncEnabled', self::TEST_BOOLEAN, self::TEST_BOOLEAN],
            'defaultUserOwner'    => ['defaultUserOwner', $user, $user],
        ];
    }

    public function testTransportRelation()
    {
        $transport = $this->getMockForAbstractClass('Oro\Bundle\IntegrationBundle\Entity\Transport');
        $this->assertAttributeEmpty('transport', $this->entity);

        $this->entity->setTransport($transport);
        $this->assertSame($transport, $this->entity->getTransport());

        $this->entity->clearTransport();
        $this->assertAttributeEmpty('transport', $this->entity);
    }

    public function testSynchronizationSettings()
    {
        $value = $this->entity->getSynchronizationSettings();
        $this->assertNotEmpty($value);

        $this->assertInstanceOf('Oro\Bundle\IntegrationBundle\Model\IntegrationSettings', $value);

        $this->entity->setSynchronizationSettings(new IntegrationSettings());

        $this->assertNotSame($value, $this->entity->getSynchronizationSettings());
    }
}
