<?php
namespace Framework\Cookie;
/**
 * Description of CookieManager
 *
 * @author mochiwa
 */
class CookieManager {
    
    /**
     * The cookies store
     * @var ICookie
     */
    private $cookieStore;
    
    
    public function __construct(ICookieStore $cookieStore){
        $this->cookieStore=$cookieStore;
    }
    
    public function hasCookie(string $cookieName) : bool
    {
        return isset($this->cookieStore->getStore()[$cookieName]);
    }
    
  
    /**
     * Append a cookie the cookie store, if a cookie with 
     * the cookie name already exist then throw CookieStoreException
     * @param string $cookieName
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param string $secure
     * @param bool $httponly
     * @throws CookieStoreException
     */
    public function addCookie(string $cookieName,string $value = '', int $expire = 1, string $path = '', string $domain = '', string $secure = '', bool $httponly = false)
    {
        if($this->hasCookie($cookieName)){
            throw new CookieStoreException("A cookie with this name already exist in cookie store");
        }
        $this->cookieStore->setCookie($cookieName, $value,$expire,$path,$domain,$secure,$httponly);
    }

    /**
     * Set a cookie to the cookieStore , if cookie already exist then <b>replace</b> it 
     * @param string $cookieName
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param string $secure
     * @param bool $httponly
     */
    public function setCookie(string $cookieName,string $value = '', int $expire = 1, string $path = '', string $domain = '', string $secure = '', bool $httponly = false){
        $this->cookieStore->setCookie($cookieName, $value,$expire,$path,$domain,$secure,$httponly);
    }
    /**
     * erase the cookie from the cookie store,
     * if store hasn't the cookie throw Exception
     * @param string $cookieName
     * @throws CookieStoreException
     */
    public function eraseCookie(string $cookieName)
    {
        if(!$this->hasCookie($cookieName)){
            throw new CookieStoreException('The cookie store has not the cookie <'.$cookieName.'>');
        }
        $this->cookieStore->erase($cookieName);
    }
}
