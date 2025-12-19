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

  Scenario: Create new user
    Given empty db
    When send "POST" to request "/users" with name "Valik" age 92 money 45000 "with" visa
    Then db should have 1 user
    And user 1 should have name "Valik" age 92 money 45000 "with" visa

  Scenario: Update user
    Given initialize db with default values
    When send "PATCH" to request "/users/1" with name "Oleg" age 52 money 666 "without" visa
    Then user 1 should have name "Oleg" age 52 money 666 "without" visa

    Scenario: Delete user
      Given initialize db with default values
      When send "DELETE" to request "/users/1"
      Then db should have 1 user