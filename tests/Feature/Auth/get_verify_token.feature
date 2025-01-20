
Feature: Verify auth token
    In order to authenticate users and protect resources
    As a consumer of the API
    I want to verify tokens to grant access to resources

    @auth
    Scenario: Verify a valid token
        Given I send a GET request to "/api/auth/verify-token" with headers:
            | HTTP_AUTHORIZATION | Bearer <validToken> |
        Then the response status code should be 200
        And the response content should match JSON:
        """
          {
              "id": "<string>",
              "email": "test@test.com"
          }
        """

    Scenario: Fail to verify an invalid token
        Given I send a GET request to "/api/auth/verify-token" with headers:
            | HTTP_AUTHORIZATION | Bearer invalid-token |
        Then the response status code should be 401
        And the response content should be:
        """
          {
            "error": "Invalid or expired token"
          }
        """

    Scenario: Fail to verify when token is missing
        Given I send a GET request to "/api/auth/verify-token"
        Then the response status code should be 400
        And the response content should be:
        """
          {
            "error": "Token missing"
          }
        """
