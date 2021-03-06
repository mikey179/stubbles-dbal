<?php
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  stubbles\db
 */
namespace stubbles\db\config;
use stubbles\lang\SecureString;
/**
 * Test for stubbles\db\config\DatabaseConfiguration.
 *
 * @group  db
 * @group  config
 */
class DatabaseConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  DatabaseConfiguration
     */
    private $dbConfig;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->dbConfig = new DatabaseConfiguration('foo', 'dsn:bar');
    }

    /**
     * @test
     */
    public function hasGivenId()
    {
        $this->assertEquals('foo', $this->dbConfig->id());
    }

    /**
     * @test
     */
    public function hasGivenDsn()
    {
        $this->assertEquals('dsn:bar', $this->dbConfig->dsn());
    }

    /**
     * @test
     */
    public function hasNoUserNameByDefault()
    {
        $this->assertNull($this->dbConfig->getUserName());
    }

    /**
     * @test
     */
    public function userNameCanBeSet()
    {
        $this->assertEquals('mikey', $this->dbConfig->setUserName('mikey')->getUserName());
    }

    /**
     * @test
     */
    public function hasNoPasswordByDefault()
    {
        $this->assertNull($this->dbConfig->getPassword());
    }

    /**
     * @test
     */
    public function passwordCanBeSet()
    {
        $this->assertEquals('secret', $this->dbConfig->setPassword(SecureString::create('secret'))->getPassword());
    }

    /**
     * @test
     */
    public function hasNoDriverOptionsByDefault()
    {
        $this->assertFalse($this->dbConfig->hasDriverOptions());
        $this->assertEquals([], $this->dbConfig->driverOptions());
    }

    /**
     * @test
     */
    public function driverOptionsCanBeSet()
    {
         $this->dbConfig->setDriverOptions(['foo' => 'bar']);
         $this->assertTrue($this->dbConfig->hasDriverOptions());
         $this->assertEquals(['foo' => 'bar'], $this->dbConfig->driverOptions());
    }

    /**
     * @test
     */
    public function hasNoInitialQueryByDefault()
    {
        $this->assertFalse($this->dbConfig->hasInitialQuery());
        $this->assertNull($this->dbConfig->initialQuery());
    }

    /**
     * @test
     */
    public function initialQueryCanBeSet()
    {
         $this->dbConfig->setInitialQuery('set names utf8');
         $this->assertTrue($this->dbConfig->hasInitialQuery());
         $this->assertEquals('set names utf8', $this->dbConfig->initialQuery());
    }

    /**
     * @test
     * @since  2.1.0
     */
    public function hasNoDetailsByDefault()
    {
        $this->assertNull($this->dbConfig->details());
    }

    /**
     * @test
     * @since  2.1.0
     */
    public function hasDetailsWhenSet()
    {
        $this->assertEquals('some interesting details about the db',
                            $this->dbConfig->setDetails('some interesting details about the db')
                                           ->details()
        );
    }

    /**
     * @test
     */
    public function createFromArrayMinimalProperties()
    {
        $dbConfig = DatabaseConfiguration::fromArray('foo', 'dsn:bar', []);
        $this->assertEquals('foo', $dbConfig->id());
        $this->assertEquals('dsn:bar', $dbConfig->dsn());
        $this->assertNull($dbConfig->getUserName());
        $this->assertNull($dbConfig->getPassword());
        $this->assertFalse($dbConfig->hasDriverOptions());
        $this->assertEquals([], $dbConfig->driverOptions());
        $this->assertFalse($dbConfig->hasInitialQuery());
        $this->assertNull($dbConfig->initialQuery());
        $this->assertNull($this->dbConfig->details());
    }

    /**
     * @test
     */
    public function createFromArrayFullProperties()
    {
        $dbConfig = DatabaseConfiguration::fromArray('foo',
                                                     'dsn:bar',
                                                     ['username'      => 'root',
                                                      'password'      => 'secret',
                                                      'initialQuery'  => 'SET names utf8',
                                                      'details'       => 'some interesting details about the db'
                                                     ]
                    );
        $this->assertEquals('foo', $dbConfig->id());
        $this->assertEquals('dsn:bar', $dbConfig->dsn());
        $this->assertEquals('root', $dbConfig->getUserName());
        $this->assertEquals('secret', $dbConfig->getPassword());
        $this->assertFalse($dbConfig->hasDriverOptions());
        $this->assertEquals([], $dbConfig->driverOptions());
        $this->assertTrue($dbConfig->hasInitialQuery());
        $this->assertEquals('SET names utf8', $dbConfig->initialQuery());
        $this->assertEquals('some interesting details about the db', $dbConfig->details());
    }

    /**
     * @test
     * @since  2.2.0
     */
    public function returnsNullIfPropertyNotSet()
    {
        $this->assertNull(DatabaseConfiguration::fromArray('foo', 'dsn:bar', [])->property('baz'));
    }

    /**
     * @test
     * @since  2.2.0
     */
    public function returnsDefaultIfPropertyNotSet()
    {
        $this->assertEquals('bar',
                            DatabaseConfiguration::fromArray('foo', 'dsn:bar', [])->property('baz', 'bar')
        );
    }

    /**
     * @test
     * @since  2.2.0
     */
    public function returnsValueIfPropertySet()
    {
        $this->assertEquals('example',
                            DatabaseConfiguration::fromArray('foo', 'dsn:bar', ['baz' => 'example'])->property('baz', 'bar')
        );
    }
}
