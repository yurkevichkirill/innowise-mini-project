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
    Then get exception

  Scenario: Delete concrete user in db
    Given db is empty
    When initialize default values
    And delete user 2
    And get user with id 2
    Then get exception

  Scenario: Delete non-existing user in db
    Given db is empty
    When delete user 2
    Then get exception