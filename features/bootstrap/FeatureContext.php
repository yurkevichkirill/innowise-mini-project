<?php

use App\Logger;
use App\Models\User;
use App\Services\UserRepository;
use App\TestDB;
use Behat\Behat\Context\Context;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

/**
 * Defines application features from the specific context.
 */
#[AllowDynamicProperties]
class FeatureContext implements Context
{
    private ?TestDB $db = null;
    private ?UserRepository $repo = null;
    private ?User $lastUser = null;
    private ?Throwable $lastException = null;

    private array $defaultValues = [
        ['Valik', 92, 45000, true],
        ['Seriy', 54, 3400, false]
    ];
    protected function setUp(): void
    {
        $this->db = new TestDB(getenv('TEST_DB_DSN'));
        $pdo = $this->db->getConnection();

        $pdo->exec('DROP TABLE IF EXISTS users');
        $pdo->exec('CREATE TABLE users (
            id INTEGER PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            age INT NOT NULL,
            money FLOAT NOT NULL,
            has_visa INTEGER NOT NULL
            )'
        );

        $logger = new Logger();

        $this->repo = new UserRepository($this->db, $logger);
    }
    /**
     * @Given /^db is empty$/
     */
    public function dbIsEmpty(): void
    {
        if(is_null($this->db)){
            $this->setUp();
        }
        assertEmpty($this->repo->getUsers());
    }

    /**
     * @When /^initialize default values$/
     */
    public function initializeDefaultValues(): void
    {
        foreach ($this->defaultValues as $row) {
            $this->repo->addUser(...$row);
        }
    }

    /**
     * @When /^I add user "([^"]*)" with age (\d+) money (\d+) "([^"]*)" visa$/
     */
    public function iAddUserWithAgeMoneyVisa(string $name, int $age, float $money, string $visaStr): void
    {
        $has_visa = $visaStr === 'with';
        $this->repo->addUser($name, $age, $money, $has_visa);
    }

    /**
     * @Given /^user with id (\d+) should be "([^"]*)" with age (\d+) money (\d+) "([^"]*)" visa$/
     */
    public function userWithIdShouldBeWithAgeMoneyVisa(int $id, string $name, int $age, float $money, string $visaStr): void
    {
        $has_visa = $visaStr === 'with';

        assertEquals($name, $this->repo->getUser($id)->getName());
        assertEquals($age, $this->repo->getUser($id)->getAge());
        assertEquals($money, $this->repo->getUser($id)->getMoney());
        assertTrue($has_visa);
    }

    /**
     * @Given /^edit name to "([^"]*)" age to (\d+) money to (\d+) "([^"]*)" visa of user (\d+)$/
     */
    public function editNameToAgeToMoneyToVisaOfUser(string $name, int $age, float $money, string $visaStr, int $id): void
    {
        $has_visa = $visaStr === 'with';
        try {
            $this->repo->updateUser($id, $name, $age, $money, $has_visa);
        } catch (Throwable $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Given /^delete user (\d+)$/
     */
    public function deleteUser($id): void
    {
        $this->lastException = null;
        try {
            $this->repo->deleteUser($id);
        } catch (Throwable $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @When /^add user "([^"]*)" with age (\d+) money (\d+) "([^"]*)" visa$/
     */
    public function addUserWithAgeMoneyVisa(string $name, int $age, float $money, string $visaStr): void
    {
        $this->repo->addUser($name, $age, $money, $visaStr);
    }

    /**
     * @Then /^get (\d+) users from db$/
     */
    public function getUsersFromDb(int $count): void
    {
        assertEquals($count, count($this->repo->getUsers()));
    }

    /**
     * @Then /^user (\d+) should not exist$/
     */
    public function userShouldNotExist($id): void
    {
        assertNull($this->repo->getUser($id));
    }

    /**
     * @Then /^user (\d+) should exist$/
     */
    public function userShouldExist($id): void
    {
        assertTrue($this->repo->existUser($id));
    }

    /**
     * @When /^get user with id (\d+)$/
     */
    public function getUserWithId($id): void
    {
        $this->lastUser = null;
        $this->lastUser = $this->repo->getUser($id);
    }

    /**
     * @Then /^get exception$/
     */
    public function getException(): void
    {
        assertNotNull($this->lastException);
    }

    /**
     * @Then /^should get last user null$/
     */
    public function shouldGetLastUserNull(): void
    {
        assertNull($this->lastUser);
    }
}
