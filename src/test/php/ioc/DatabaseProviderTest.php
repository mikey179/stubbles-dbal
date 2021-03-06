<?php
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  stubbles\db
 */
namespace stubbles\db\ioc;
/**
 * Test for stubbles\db\ioc\DatabaseProvider.
 *
 * @group  db
 * @group  ioc
 */
class DatabaseProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  DatabaseProvider
     */
    private $databaseProvider;
    /**
     * mocked connection provider
     *
     * @type  \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockConnectionProvider;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockConnectionProvider = $this->getMockBuilder('stubbles\db\ioc\ConnectionProvider')
                                             ->disableOriginalConstructor()
                                             ->getMock();
        $this->databaseProvider       = new DatabaseProvider($this->mockConnectionProvider);
    }

    /**
     * @test
     */
    public function createsInstanceWithConnectionFromConnectionProvider()
    {
        $this->mockConnectionProvider->expects($this->once())
                                     ->method('get')
                                     ->with($this->equalTo('foo'))
                                     ->will($this->returnValue($this->getMock('stubbles\db\DatabaseConnection')));
        $this->assertInstanceOf('stubbles\db\Database',
                                $this->databaseProvider->get('foo')
        );
    }
}
