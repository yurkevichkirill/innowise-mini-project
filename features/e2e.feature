Feature:
  User HTTP API

  Scenario: Get all users
    Given initialize db with default values
    When send "GET" to request "/api/users/"
    Then response should contain user with id 1 name "Valik" age 92 money 45000 "with" visa
    Then response should contain user with id 2 name "Seriy" age 54 money 3400 "without" visa
    And response code should be 200

  Scenario: Get concrete user
    Given initialize db with default values
    When send "GET" to request "/api/users/1"
    Then response should contain user with id 1 name "Valik" age 92 money 45000 "with" visa
    And response code should be 200

  Scenario: Get non exist user
    Given empty db
    When send "GET" to request "/api/users/1"
    Then response should contain "User Not Found"
    And response code should be 404

  Scenario: Create new user
    Given empty db
    When send "POST" to request "/api/users" with name "Valik" age 92 money 45000 "with" visa
    Then response should contain user with id 1 name "Valik" age 92 money 45000 "with" visa
    And response code should be 201

  Scenario: Update user
    Given initialize db with default values
    When send "PATCH" to request "/api/users/1" with name "Oleg" age 52 money 666 "without" visa
    Then response should contain user with id 1 name "Oleg" age 52 money 666 "without" visa
    And response code should be 200

  Scenario: Update non exist user
    Given empty db
    When send "PATCH" to request "/api/users/1" with name "Oleg" age 52 money 666 "without" visa
    Then response should contain "User Not Found"
    And response code should be 404

  Scenario: Delete user
    Given initialize db with default values
    When send "DELETE" to request "/api/users/1"
    Then db should have 1 user
    And response body should be empty
    And response code should be 204

  Scenario: Delete non exist user
    Given empty db
    When send "DELETE" to request "/api/users/1"
    Then response should contain "User Not Found"
    And response code should be 404