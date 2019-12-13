<?php
include "CookieAdapater.php";
/**
 * Description of CookieManagerTest
 *
 * @author mochiwa
 */
class CookieManagerTest extends PHPUnit\Framework\TestCase{
    private $manager;
    private $cookieStoreAdapter;
    
    
    public function setUp()
    {
        $this->cookieStoreAdapter=new CookieAdapater();
        $this->manager=new Framework\Cookie\CookieManager($this->cookieStoreAdapter);
    }
            
    function test_hasCookie_shouldReturnFalse_whenUserNavgiatorHasNotTheCookie()
    {
        $this->assertFalse($this->manager->hasCookie('myCookie'));
    }
    
    function test_hasCookie_shouldReturnTrue_whenUserNavigatorHasTheCookie()
    {
        $this->cookieStoreAdapter->setCookie('myCookie', '');
        $this->assertTrue($this->manager->hasCookie('myCookie'));
    }
    
    function test_addCookie_shouldAppendCookieToCookieStore_whenCookieNameIsNotAlreadyUsed(){
        $this->manager->addCookie('aCookieName');
        $this->assertTrue($this->manager->hasCookie('aCookieName'));
    }
    
    function test_addCookie_shouldThrowCookieStoreException_whenItsAlreadyPresent()
    {
        $this->cookieStoreAdapter->setCookie('myCookie', 'aValueforCookie');
        
        $this->expectException(Framework\Cookie\CookieStoreException::class);
        $this->manager->addCookie('myCookie','AnotherValueForACookie');
        $this->assertEquals('aValueforCookie',$this->cookieStoreAdapter->getStore()['myCookie']['value']);
    }
    
    function test_setCookie_shouldAppendCookieToCookieStore_whenCookieNameIsNotAlreadyUsed(){
        $this->manager->setCookie('aCookieName');
        $this->assertTrue($this->manager->hasCookie('aCookieName'));
    }
    
    function test_setCookie_shouldReplaceTheCookie_whenCookieIsAlreadyPresentInCookieStore()
    {
        $this->cookieStoreAdapter->setCookie('myCookie', 'aValueforCookie');
        
        $this->manager->setCookie('myCookie','AnotherValueForACookie');
        $this->assertEquals('AnotherValueForACookie',$this->cookieStoreAdapter->getStore()['myCookie']['value']);
    }
    
    
    function test_eraseCookie_shouldRepalceRemoveTheCookieFromTheCookieStore_whenCookieExist()
    {
        $this->cookieStoreAdapter->setCookie('myCookie', 'aValueforCookie');
        $this->manager->eraseCookie('myCookie');
        $this->assertFalse(isset($this->cookieStoreAdapter->getStore()['myCookie']));
    }
    function test_eraseCookie_shouldThrowCookieStoreException_whenCookieStoreHasNotTheCookie()
    {
        $this->expectException(Framework\Cookie\CookieStoreException::class);
        $this->manager->eraseCookie('myCookie');
    }
}
