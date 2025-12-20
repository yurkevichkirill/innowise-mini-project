Feature:
  User HTTP API

  Scenario: Get all users
    Given initialize db with default values
    When send "GET" to request "/users/"
    Then response should contain "Valik"

  Scenario: Get concrete user
    Given initialize db with default values
    When send "GET" to request "/users/1"
    Then response should contain "Valik"

  Scenario: Get non exist user
    Given empty db
    When send "GET" to request "/users/1"
    Then response should contain "No User Found"

  Scenario: Create new user
    Given empty db
    When send "POST" to request "/users" with name "Valik" age 92 money 45000 "with" visa
    Then response should contain name "Valik" age 92 money 45000 "with" visa

  Scenario: Update user
    Given initialize db with default values
    When send "PATCH" to request "/users/1" with name "Oleg" age 52 money 666 "without" visa
    Then response should contain name "Oleg" age 52 money 666 "without" visa

  Scenario: Update non exist user
    Given empty db
    When send "PATCH" to request "/users/1" with name "Oleg" age 52 money 666 "without" visa
    Then response should contain "Object not found"

  Scenario: Delete user
    Given initialize db with default values
    When send "DELETE" to request "/users/1"
    Then db should have 1 user

  Scenario: Delete non exist user
    Given empty db
    When send "DELETE" to request "/users/1"
    Then response should contain "Object not found"