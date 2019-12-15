<?php

namespace Test\Framework\Middleware;

use App\Identity\Model\User\User;
use Framework\Acl\AbstractTarget;
use Framework\Acl\ACL;
use Framework\Acl\Role;
use Framework\Acl\Rule;
use Framework\Acl\Target;
use Framework\Middleware\ACLMiddleware;
use Framework\Router\Route;
use Framework\Session\SessionManager;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Description of ACLMiddlewareTest
 *
 * @author mochiwa
 */
class ACLMiddlewareTest extends TestCase {

    private $session;
    private $acl;
    private $middleware;

    protected function setUp() {
        $this->session = $this->createMock(SessionManager::class);
        $this->acl = $this->createMock(ACL::class);
        $this->middleware = new ACLMiddleware($this->session, $this->acl);
    }

    protected function mockHandler() {
        return $this->getMockBuilder(RequestHandlerInterface::class)->setMethods(['handle'])->getMock();
    }

    private function requestWithRoute(string $name = 'my.route', string $target = '/app/service', array $attributes = []): ServerRequest {
        return (new ServerRequest('GET', 'an.url'))->withAttribute(Route::class, new Route($name, $target, $attributes));
    }

    function test_process_shouldSendToNextHandler_whenRequestNotContainRoute() {
        $request=$this->requestWithRoute()->withoutAttribute(Route::class);
        $handler = $this->mockHandler();
        $handler->expects($this->once())->method('handle');
        $this->middleware->process($request, $handler);
    }

    function test_process_shouldSetCurrentRoleToVisitor_whenSessionHasNotUser() {
        $request = $this->requestWithRoute();

        $this->session->expects($this->once())->method('get')->with(SessionManager::CURRENT_USER_KEY)->willReturn(null);
        $this->acl->expects($this->once())->method('getRole')->with('visitor')->willReturn(Role::of('visitor'));

        $this->middleware->process($request, $this->mockHandler());
    }
    function test_process_shouldSetCurrentRoleToUser_whenSessionDoesContainUser() {
        $request = $request = $this->requestWithRoute();
        $user = $this->createMock(User::class);
        
        $this->session->expects($this->once())->method('get')->with(SessionManager::CURRENT_USER_KEY)->willReturn($user);
        $this->acl->expects($this->once())->method('getRole')->with('user')->willReturn(Role::of('user'));

        $this->middleware->process($request, $this->mockHandler());
    }
    function test_process_shouldSetCurrentRoleToAdmin_whenUserInSessionIsAdmin() {
        $request = $request = $this->requestWithRoute();
        $user = $this->createMock(User::class);
        
        $user->expects($this->once())->method('isAdmin')->willReturn(true);
        $this->session->expects($this->once())->method('get')->with(SessionManager::CURRENT_USER_KEY)->willReturn($user);
        $this->acl->expects($this->once())->method('getRole')->with('admin')->willReturn(Role::of('admin'));

        $this->middleware->process($request, $this->mockHandler());
    }

    
    function test_process_shouldTestIfCurrentRoleHasAdminAccess_whenRouteNameContainAdmin() {
        $request = $this->requestWithRoute('admin.parking');
        $user = $this->createMock(User::class);
        
        $this->session->expects($this->once())->method('get')->with(SessionManager::CURRENT_USER_KEY)->willReturn($user);
        $user->expects($this->once())->method('isAdmin')->willReturn(true);
        $this->acl->expects($this->once())->method('getRole')->with('admin')->willReturn(Role::of('admin'));
        $this->acl->expects($this->once())->method('isAllowed')->with(Role::of('admin'), Rule::Allow(AbstractTarget::URL('admin')));

        $this->middleware->process($request, $this->mockHandler());
    }
    
    

    function test_process_shouldTestActionOfRouteForRole_whenRouteNotContainAdmin() {
        $request = $request = $this->requestWithRoute('parking.view', 'app/service', ['action' => 'create']);
        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('isAdmin')->willReturn(true);
        $this->session->expects($this->once())->method('get')->with(SessionManager::CURRENT_USER_KEY)->willReturn($user);
        $this->acl->expects($this->once())->method('getRole')->with('admin')->willReturn(Role::of('admin'));
        $this->acl->expects($this->once())->method('isAllowed')->with(Role::of('admin'), Rule::Allow(AbstractTarget::ControllerAction('app/service', 'create')));

        $this->middleware->process($request, $this->mockHandler());
    }

    function test_process_shouldTestTheWholeControllerAccess_whenTheControllerLinkHasNoAction() {
        $request = $request = $this->requestWithRoute('parking.view', 'app/service');
        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('isAdmin')->willReturn(true);
        $this->session->expects($this->once())->method('get')->with(SessionManager::CURRENT_USER_KEY)->willReturn($user);
        $this->acl->expects($this->once())->method('getRole')->with('admin')->willReturn(Role::of('admin'));
        $this->acl->expects($this->once())->method('isAllowed')->with(Role::of('admin'), Rule::Allow(AbstractTarget::Controller('app/service')));

        $this->middleware->process($request, $this->mockHandler());
    }

    function test_process_shouldRemoveRouteFromRequest_whenACLNotAllowsTheAction() {
        $request = $request = $this->requestWithRoute('parking.view', 'app/service', ['action' => 'create']);
        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('isAdmin')->willReturn(false);
        $this->session->expects($this->once())->method('get')->with(SessionManager::CURRENT_USER_KEY)->willReturn($user);
        $this->acl->expects($this->once())->method('getRole')->with('user')->willReturn(Role::of('user'));
        $this->acl->expects($this->once())->method('isAllowed')->with(Role::of('user'), Rule::Allow(AbstractTarget::ControllerAction('app/service', 'create')))->willReturn(false);


        $handler = $this->mockHandler();
        $handler->expects($this->once())->method('handle')->with($request->withAttribute(Route::class, null));
        $this->middleware->process($request, $handler);
    }

    /* function test_process_checkIfCurrentRoleCanUseAfunctionFromAnAdminPath(){
      $request=$request=$this->requestWithRoute('parking.view','admin',['action'=>'create']);
      $user=$this->createMock(User::class);
      $user->expects($this->once())->method('isAdmin')->willReturn(false);
      $this->session->expects($this->once())->method('get')->with(SessionManager::CURRENT_USER_KEY)->willReturn($user);
      $this->acl->expects($this->once())->method('getRole')->with('user')->willReturn(Role::of('visitor'));

      $this->acl->expects($this->once())->method('isAllowed')->with(Role::of('visitor'), Rule::Allow(AbstractTarget::URL('admin')))->willReturn(false);


      $handler=$this->mockHandler();
      $handler->expects($this->once())->method('handle')->with($request->withAttribute(Route::class, null));
      $this->middleware->process($request, $handler);
      } */
}
