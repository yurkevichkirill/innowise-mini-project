<?php

declare(strict_types=1);

use App\Container;
use App\Logger;
use App\Router;
use App\Services\ConnectionServiceInterface;
use App\TestDB;
use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\When;
use Behat\Step\Then;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertStringContainsString;

class FeatureContextE2E implements Context
{
    private ?Container $container = null;
    private ?Router $router = null;
    private ?TestDB $db = null;
    private array $defaultValues = [
        ['Valik', 92, 45000, true],
        ['Seriy', 54, 3400, false]
    ];
    private ?string $lastResponse = null;

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        $this->container = new Container();

        $this->container->bind(ConnectionServiceInterface::class, TestDB::class);

        $loader = new FilesystemLoader(__DIR__ . '/../../views');
        $twig = new Environment($loader, [
            'cache' => false
        ]);
        $this->container->singleton(Environment::class, $twig);

        $logger = new Logger();
        $this->container->singleton(LoggerInterface::class, $logger);

        $this->router = new Router($this->container);
        $this->router->initializeControllers();

        $this->setUpDb();
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function setUpDb(): void
    {
        $this->db = $this->container->get(TestDB::class);

        $this->db->getConnection()->exec('DROP TABLE IF EXISTS users');
        $this->db->getConnection()->exec('CREATE TABLE users (
            id INTEGER PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            age INT NOT NULL,
            money FLOAT NOT NULL,
            has_visa INTEGER NOT NULL
            )'
        );
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Given('initialize db with default values')]
    public function initializeDbWithDefaultValues(): void
    {
        if(!isset($this->router)) {
            $this->setUp();
        }

        $stmt = $this->db->getConnection()->prepare("INSERT INTO users (name, age, money, has_visa) VALUES 
                                                   (?, ?, ?, ?)"
        );

        foreach ($this->defaultValues as $row) {
            $stmt->execute($row);
        }
    }

    /**
     * @throws GuzzleException
     */
    #[When('send :arg1 to request :arg2')]
    public function sendToRequest($method, $uri): void
    {
        $client = new Client([
            'base_uri' => 'http://nginx',
            'timeout' => 2.0,
            'http_errors' => false,
        ]);

        $this->lastResponse = $client
            ->request($method, $uri, [
                'headers' => [
                    'X-Test-Mode' => true
                ]
            ])
            ->getBody()
            ->getContents();
    }

    #[Then('response should contain :arg1')]
    public function responseShouldContain($data): void
    {
        assertStringContainsString($data, $this->lastResponse);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     * @throws NotFoundExceptionInterface
     */
    #[Given('empty db')]
    public function emptyD(): void
    {
        if(!isset($this->router)) {
            $this->setUp();
        }
    }

    /**
     * @throws GuzzleException
     */
    #[When('send :arg1 to request :arg2 with name :arg3 age :arg5 money :arg6 :arg4 visa')]
    public function sendToRequestWithNameAgeMoneyVisa($method, $uri, $name, $age, $money, $visaStr): void
    {
        $has_visa = $visaStr === 'with';
        $jsonData = json_encode([
            'name' => $name,
            'age' => $age,
            'money' => $money,
            'has_visa' => $has_visa
        ]);

        $client = new Client([
            'base_uri' => 'http://nginx',
            'timeout' => 2.0,
            'http_errors' => false
        ]);

        $this->lastResponse = $client->request($method, $uri, [
            'json' => [
                'name' => $name,
                'age' => $age,
                'money' => $money,
                'has_visa' => $has_visa
            ],
            'headers' => [
                'X-Test-Mode' => true
            ]
        ])->getBody()->getContents();
    }

    #[Then('db should have :arg1 user')]
    public function dbShouldHaveUser($count): void
    {
        $stmt = $this->db->getConnection()->query("SELECT COUNT(id) FROM users");
        $result = $stmt->fetchColumn();
        assertEquals($count, $result);
    }

    #[Then('user :arg3 should have name :arg1 age :arg4 money :arg5 :arg2 visa')]
    public function userShouldHaveNameAgeMoneyVisa($id, $name, $age, $money, $visaStr): void
    {
        $has_visa = $visaStr === 'with';

        $stmt = $this->db->getConnection()->prepare('SELECT name FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $resultName = $stmt->fetchColumn();
        $stmt = $this->db->getConnection()->prepare('SELECT age FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $resultAge = $stmt->fetchColumn();
        $stmt = $this->db->getConnection()->prepare('SELECT money FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $resultMoney = $stmt->fetchColumn();
        $stmt = $this->db->getConnection()->prepare('SELECT has_visa FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $resultVisa = $stmt->fetchColumn();

        assertEquals($name, $resultName);
        assertEquals($age, $resultAge);
        assertEquals($money, $resultMoney);
        assertEquals($has_visa, $resultVisa);
    }

    #[Then('response should contain name :arg1 age :arg3 money :arg4 :arg2 visa')]
    public function responseShouldContainNameAgeMoneyVisa($name, $age, $money, $visaStr): void
    {
        $has_visa = $visaStr === 'with';
        $testData = [
            'user' => [
                'id' => 1,
                'name' => (string)$name,
                'age' => (int)$age,
                'money' => (float)$money,
                'has_visa' => $has_visa
            ]
        ];
        $jsonTestData = json_encode($testData);

        assertEquals($jsonTestData, $this->lastResponse);
    }
}