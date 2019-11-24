<?php
namespace Test\App;
require_once 'controller/TestModule.php';

/**
 * Description of AppTest
 *
 * @author mochiwa
 */
class AppTest extends \PHPUnit\Framework\TestCase{
    
    public function test_run_shouldReturnError404NotFound_whenNoRouteFoundForTheRequest(){
        $app=new \App\Application();
        $app->addModule(\App\Controller\ErrorController::class);
        $response=$app->run(new \GuzzleHttp\Psr7\Request('GET','/notExistingPage'));
        $this->assertSame(404,$response->getStatusCode());
    }
    
    public function test_run_shouldReturnTheModulePage_whenItFound(){
        $app=new \App\Application();
        $app->addModule(TestModule::class);
        $response=$app->run(new \GuzzleHttp\Psr7\Request('GET','/module'));
        $this->assertNotNull($response);
    }
}
