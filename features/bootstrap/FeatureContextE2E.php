<?php

declare(strict_types=1);

use App\Container;
use App\DB;
use App\Exceptions\ContainerException;
use App\Logger;
use App\Router;
use Behat\Behat\Context\Context;
use Behat\Hook\AfterScenario;
use Behat\Hook\BeforeScenario;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;

final class FeatureContextE2E implements Context
{
    private ?Container $container = null;

    private ?Router $router = null;

    private ?DB $db = null;

    private array $defaultValues = [
        ['Valik', 92, 45_000, true],
        ['Seriy', 54, 3_400, false],
    ];

    private ?ResponseInterface $lastResponse = null;

    private ?array $lastResponseData = null;

    #[BeforeScenario]
    public static function putEnvs(): void
    {
        putenv('TEST_MODE=yes');

        $file = __DIR__ . '/../../.env.test';
        file_put_contents($file, 'TEST_MODE=yes');
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function setUp(): void
    {
        $this->container = new Container();

        $loader = new FilesystemLoader(__DIR__ . '/../../views');
        $twig = new Environment($loader, [
            'cache' => false,
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
     * @throws ContainerExceptionInterface|ContainerException
     */
    private function setUpDb(): void
    {
        $this->db = $this->container->get(DB::class);

        $this->db->getConnection()->exec('DROP TABLE IF EXISTS users');
        $this->db->getConnection()->exec(
            'CREATE TABLE users (
            id INTEGER PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            age INT NOT NULL,
            money FLOAT NOT NULL,
            has_visa INTEGER NOT NULL
            )',
        );
    }

    #[Given('initialize db with default values')]
    public function initializeDbWithDefaultValues(): void
    {
        if (!isset($this->router)) {
            $this->setUp();
        }

        $stmt = $this->db->getConnection()->prepare(
            'INSERT INTO users (name, age, money, has_visa) VALUES 
                                                   (?, ?, ?, ?)',
        );

        foreach ($this->defaultValues as $row) {
            $stmt->execute($row);
        }
    }

    /**
     * @throws GuzzleException
     */
    #[When('send :arg1 to request :arg2')]
    public function sendToRequest(string $method, string $uri): void
    {
        $client = new Client([
            'base_uri' => 'http://nginx',
            'timeout' => 2.0,
            'http_errors' => false,
        ]);

        $this->lastResponse = $client
            ->request($method, $uri);
    }

    #[Then('response should contain :arg1')]
    public function responseShouldContain(string $data): void
    {
        $jsonResult = $this->lastResponse->getBody()->getContents();
        $result = array_values(json_decode($jsonResult, true))[0];
        assertEquals($data, $result);
    }

    #[Given('empty db')]
    public function emptyDb(): void
    {
        if (!isset($this->router)) {
            $this->setUp();
        }
    }

    /**
     * @throws GuzzleException
     */
    #[When('send :arg1 to request :arg2 with name :arg3 age :arg5 money :arg6 :arg4 visa')]
    public function sendToRequestWithNameAgeMoneyVisa(string $method, string $uri, string $name, int $age, float $money, string $visaStr): void
    {
        $has_visa = $visaStr === 'with';

        $client = new Client([
            'base_uri' => 'http://nginx',
            'timeout' => 2.0,
            'http_errors' => false,
        ]);

        $this->lastResponse = $client->request($method, $uri, [
            'json' => [
                'name' => $name,
                'age' => $age,
                'money' => $money,
                'has_visa' => $has_visa,
            ],
        ]);
    }

    #[Then('db should have :arg1 user')]
    public function dbShouldHaveUser($count): void
    {
        $stmt = $this->db->getConnection()->query('SELECT COUNT(id) FROM users');
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

    /**
     * @Then /^response should contain user with id (\d+) name "([^"]*)" age (\d+) money (\d+) "([^"]*)" visa$/
     */
    public function responseShouldContainUserWithIdNameAgeMoneyVisa(int $id, string $name, int $age, float $money, string $visaStr): void
    {
        $has_visa = $visaStr === 'with';
        $testData = ['user' => [
            'id' => $id,
            'name' => $name,
            'age' => $age,
            'money' => $money,
            'has_visa' => $has_visa,
        ]];

        if (!isset($this->lastResponseData)) {
            $this->lastResponseData = json_decode($this->lastResponse->getBody()->getContents(), true);
        }

        if (count($this->lastResponseData) > 1) {
            $resultObj = $this->lastResponseData[$id - 1];
        } else {
            $resultObj = $this->lastResponseData;
        }

        assertEquals($testData, $resultObj);
    }

    /**
     * @Given /^response code should be (\d+)$/
     */
    public function responseCodeShouldBe($code): void
    {
        assertEquals($code, $this->lastResponse->getStatusCode());
    }

    /**
     * @Then /^response body should be empty$/
     */
    public function responseBodyShouldBeEmpty(): void
    {
        assertEmpty($this->lastResponse->getBody()->getContents());
    }

    #[AfterScenario]
    public static function resetEnvs(): void
    {
        putenv('TEST_MODE=no');

        $file = __DIR__ . '/../../.env.test';
        file_put_contents($file, 'TEST_MODE=no');
    }
}
