Feature: Authentication API
    In order to authenticate users and protect resources
    As a consumer of the API
    I want to auth and issue tokens

    Scenario: Issue a token with valid credentials
        Given I send a POST request to "/api/auth/login" with body:
        """
            {
                "email": "test@test.com",
                "password": "password123"
            }
        """
        Then the response status code should be 200
        And the response content should match JSON:
        """
            {
            "token": "<string>",
            "expires_at": "<datetime>"
            }
        """

    Scenario: Fail to issue a token with invalid credentials
        Given I send a POST request to "/api/auth/login" with body:
        """
            {
            "email": "test@test.com",
            "password": "wrong-password"
            }
        """
        Then the response status code should be 401
        And the response content should be:
        """
            {
            "error": "Invalid credentials"
            }
        """
