<?php

declare(strict_types=1);

namespace Unit;

use App\Controllers\User\UserController;
use App\Models\User;
use App\Services\UserServiceInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Twig\Environment;

class UserControllerTest extends TestCase
{
    private ?UserServiceInterface $service = null;
    private ?Environment $twig = null;
    protected function setUp(): void
    {
        $this->service = $this->createMock(UserServiceInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }
    public function testShowAllUsersRendersTemplate(): void
    {
        $values = [
            new User(1, 'Oleg', 23, 56.1, true),
            new User(2, 'Slavik', 44, 66.6, false)
        ];

        $this->service->method('getUsers')
            ->willReturn($values);

        $this->twig->expects($this->once())
            ->method('render')
            ->with('users.twig', ['users' => $this->service->getUsers()])
            ->willReturn('HTML');

        $controller = new UserController($this->service, $this->twig, $this->logger);

        ob_start();
        $controller->showAllUsers();
        $result = ob_get_clean();

        $this->assertSame('HTML', $result);
    }

    public function testIndexRendersTemplate(): void
    {
        $this->twig->expects($this->once())
            ->method('render')
            ->with('index.twig')
            ->willReturn('HTML');

        $service = $this->createMock(UserServiceInterface::class);
        $controller = new UserController($service, $this->twig, $this->logger);

        ob_start();
        $controller->index();
        $result = ob_get_clean();

        $this->assertSame('HTML', $result);
    }

    public function testShowConcreteUserRendersTemplate(): void
    {
        $testId = 1;
        $testUser = new User(1, 'Vitaliq', 45, 78.9, false);

        $this->service->method('getUser')
            ->with($testId)
            ->willReturn($testUser);

        $this->twig->expects($this->once())
            ->method('render')
            ->with('user.twig', ['user' => $this->service->getUser($testId)])
            ->willReturn('HTML');

        $controller = new UserController($this->service, $this->twig, $this->logger);

        ob_start();
        $controller->showUser($testId);
        $result = ob_get_clean();

        $this->assertSame('HTML', $result);
    }
}