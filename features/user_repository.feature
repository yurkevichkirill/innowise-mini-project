Feature:
  User repository db operations

  Scenario: Get operations from db
    Given db is empty
    When initialize default values
    Then get 2 users from db
    And user with id 1 should be "Valik" with age 92 money 45000 "with" visa

  Scenario: Try to get non-existent User
    Given db is empty
    When get user with id 1
    Then should get last user null

  Scenario: Add new user to db
    Given db is empty
    When add user "Nikolay" with age 55 money 23000 "with" visa
    Then user with id 1 should be "Nikolay" with age 55 money 23000 "with" visa

  Scenario: Edit concrete user in db
    Given db is empty
    When initialize default values
    And edit name to "Sergey" age to 33 money to 6600 'with' visa of user 2
    Then user with id 2 should be "Sergey" with age 33 money 6600 "with" visa

  Scenario: edit no-existing user in db
    Given db is empty
    When edit name to "Sergey" age to 33 money to 6600 'with' visa of user 2
    Then get exception

  Scenario: Delete concrete user in db
    Given db is empty
    When initialize default values
    And delete user 2
    Then user 2 should not exist

  Scenario: Delete non-existing user in db
    Given db is empty
    When delete user 2
    Then get exception

  Scenario: Is user exist
    Given db is empty
    When add user "Nikolay" with age 55 money 23000 "with" visa
    Then user 1 should exist