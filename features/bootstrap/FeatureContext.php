<?php

use App\DB;
use App\Logger;
use App\Models\UserDTO;
use App\Services\UserRepository;
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
    private ?DB $db = null;
    private ?UserRepository $repo = null;
    private ?UserDTO $lastUser = null;
    private ?Throwable $lastException = null;

    private array $defaultValues = [
        ['Valik', 92, 45000, true],
        ['Seriy', 54, 3400, false]
    ];
    protected function setUp(): void
    {
        $this->db = new DB(getenv('TEST_DB_DSN'));
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

        $this->logger = new Logger();

        $this->repo = new UserRepository($this->db, $this->logger);
    }
    /**
     * @Given /^db is empty$/
     */
    public function dbIsEmpty(): void
    {
        if(is_null($this->db)){
            $this->setUp();
        }
        assertEmpty($this->repo->getAll());
    }

    /**
     * @When /^initialize default values$/
     */
    public function initializeDefaultValues(): void
    {
        foreach ($this->defaultValues as $row) {
            $this->repo->save(new UserDTO(0, ...$row));
        }
    }

    /**
     * @Given /^user with id (\d+) should be "([^"]*)" with age (\d+) money (\d+) "([^"]*)" visa$/
     */
    public function userWithIdShouldBeWithAgeMoneyVisa(int $id, string $name, int $age, float $money, string $visaStr): void
    {
        $has_visa = $visaStr === 'with';

        assertEquals($name, $this->repo->get($id)->getName());
        assertEquals($age, $this->repo->get($id)->getAge());
        assertEquals($money, $this->repo->get($id)->getMoney());
        assertTrue($has_visa);
    }

    /**
     * @Given /^delete user (\d+)$/
     */
    public function deleteUser($id): void
    {
        $this->lastException = null;
        try {
            $this->repo->delete($id);
        } catch (Throwable $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Then /^get (\d+) users from db$/
     */
    public function getUsersFromDb(int $count): void
    {
        assertEquals($count, count($this->repo->getAll()));
    }

    /**
     * @Then /^user (\d+) should not exist$/
     */
    public function userShouldNotExist($id): void
    {
        assertNull($this->repo->get($id));
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
        $this->lastUser = $this->repo->get($id);
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
