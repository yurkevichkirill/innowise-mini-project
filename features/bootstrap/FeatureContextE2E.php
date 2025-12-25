<?php

declare(strict_types=1);

use App\Container;
use App\Logger;
use App\Router;
use App\Services\ConnectionServiceInterface;
use App\DB;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Step\Given;
use Behat\Step\When;
use Behat\Step\Then;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Twig\Environment;

class FeatureContextE2E implements \Behat\Behat\Context\Context
{
    private ?Container $container = null;
    private ?Router $router = null;
    private ?DB $db = null;
    private array $defaultValues = [
        ['Valik', 92, 45000, true],
        ['Seriy', 54, 3400, false]
    ];
    private ?string $lastResponse = null;

    #[\Behat\Hook\BeforeScenario]
    public static function putEnvs(): void
    {
        putenv("TEST_MODE=yes");

        $file = __DIR__ . "/../../.env.test";
        file_put_contents($file, "TEST_MODE=yes");
    }
    protected function setUp(): void
    {
        $this->container = new Container();

        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../views');
        $twig = new \Twig\Environment($loader, [
            'cache' => false
        ]);
        $this->container->singleton(\Twig\Environment::class, $twig);

        $logger = new Logger();
        $this->container->singleton(LoggerInterface::class, $logger);

        $this->router = new Router($this->container);
        $this->router->initializeControllers();

        $this->setUpDb();
    }

    private function setUpDb(): void
    {
        $this->db = $this->container->get(\App\DB::class);

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

    #[Given('initialize db with default values')]
    public function initializeDbWithDefaultValues()
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

    #[When('send :arg1 to request :arg2')]
    public function sendToRequest($method, $uri): void
    {
        $client = new Client([
            'base_uri' => 'http://nginx',
            'timeout' => 2.0,
            'http_errors' => false,
        ]);

        $this->lastResponse = $client
            ->request($method, $uri)
            ->getBody()
            ->getContents();
    }

    #[Then('response should contain :arg1')]
    public function responseShouldContain($data)
    {
        \PHPUnit\Framework\assertStringContainsString($data, $this->lastResponse);
    }

    #[Given('empty db')]
    public function emptyD()
    {
        if(!isset($this->router)) {
            $this->setUp();
        }
    }

    #[When('send :arg1 to request :arg2 with name :arg3 age :arg5 money :arg6 :arg4 visa')]
    public function sendToRequestWithNameAgeMoneyVisa($method, $uri, $name, $age, $money, $visaStr)
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
            ]
        ])->getBody()->getContents();
    }

    #[Then('db should have :arg1 user')]
    public function dbShouldHaveUser($count)
    {
        $stmt = $this->db->getConnection()->query("SELECT COUNT(id) FROM users");
        $result = $stmt->fetchColumn();
        \PHPUnit\Framework\assertEquals($count, $result);
    }

    #[Then('user :arg3 should have name :arg1 age :arg4 money :arg5 :arg2 visa')]
    public function userShouldHaveNameAgeMoneyVisa($id, $name, $age, $money, $visaStr)
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

        \PHPUnit\Framework\assertEquals($name, $resultName);
        \PHPUnit\Framework\assertEquals($age, $resultAge);
        \PHPUnit\Framework\assertEquals($money, $resultMoney);
        \PHPUnit\Framework\assertEquals($has_visa, $resultVisa);
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

        \PHPUnit\Framework\assertEquals($jsonTestData, $this->lastResponse);
    }

    #[\Behat\Hook\AfterScenario]
    public static function resetEnvs(): void
    {
        putenv("TEST_MODE=no");

        $file = __DIR__ . "/../../.env.test";
        file_put_contents($file, "TEST_MODE=no");
    }
}