<?php

declare(strict_types=1);

use DeployHuman\kivra\Configuration;
use DeployHuman\kivra\Exception;
use PHPUnit\Framework\TestCase;


final class ConfigurationTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
    }

    public static function tearDownAfterClass(): void
    {
    }

    public function setUp(): void
    {
        $GLOBALS['debug'] = true;
    }

    public function tearDown(): void
    {
    }

    public function testNull_and_no_Session_Init()
    {
        $GLOBALS['debug'] = false;
        session_unset();
        $this->expectException(Exception::class);
        $blankConf = new Configuration();
        $this->assertFalse($blankConf->getDebug());
    }

    public function testStorageName_Init()
    {
        $blankConf = new Configuration("testing");
        $this->assertEquals('testing', $blankConf->getStorageName());
        $this->assertTrue($blankConf->getDebug());
    }

    public function testExcessInit()
    {
        $blankConf = new Configuration();
        $this->assertTrue($blankConf->initateStorage());
        $this->assertTrue($blankConf->initateStorage());
    }

    public function testSavingAndRemovingStorage()
    {
        $conf = new Configuration();
        $this->assertInstanceOf(Configuration::class, $conf->saveToStorage(["testkey" => "testvalue"]));
        $this->assertEquals(["testkey" => "testvalue"], $conf->getStorage());
        $this->assertInstanceOf(Configuration::class, $conf->unsetFromStorage(["testkey"]));
        $this->assertEquals([], $conf->getStorage());
        $this->assertInstanceOf(Configuration::class, $conf->saveToStorage(["testkey" => "testvalue", "testkey2" => "testvalue2"]));
        $this->assertEquals(["testkey" => "testvalue", "testkey2" => "testvalue2"], $conf->getStorage());
        $this->assertInstanceOf(Configuration::class, $conf->unsetFromStorage(["testkey2"]));
        $this->assertEquals(["testkey" => "testvalue"], $conf->getStorage());
        $this->assertInstanceOf(Configuration::class, $conf->unsetFromStorage(["testkey"]));
        $this->assertEquals([], $conf->getStorage());
    }

    public function testNoClientIsSent()
    {
        $conf = new Configuration();
        $this->assertFalse($conf->isClientAuthSet());
    }

    public function testBaseURLisset()
    {
        $conf = new Configuration();
        $this->assertEquals('https://sender.api.kivra.com', $conf->getBaseUrl());
    }

    public function testBaseURLModify()
    {
        $conf = new Configuration();
        $this->assertInstanceOf(Configuration::class, $conf->setBaseUrl('https://sender.api.kivra.com/test'));
        $this->assertEquals('https://sender.api.kivra.com/test', $conf->getBaseUrl());
    }

    public function testClientIsSent()
    {
        $conf = new Configuration();
        $conf->setClient_id("test_client_id");
        $this->assertFalse($conf->isClientAuthSet());
        $conf->setClient_secret("test_client_secret");
        $this->assertTrue($conf->isClientAuthSet());
    }



    public function testSavingAccessToken()
    {
        $conf = new Configuration();
    }
}
