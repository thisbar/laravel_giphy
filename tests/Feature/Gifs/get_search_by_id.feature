Feature: Search GIF by ID
    In order to get an existing GIF
    As a logged-in user
    I want to find an GIF by ID

    @auth
    Scenario: Found an existing GIF by ID
        Given I send a GET request to "/api/gifs/3o7ZeAiCICH5bj1Esg" as an authenticated user
        Then the response status code should be 200
        And the response content should be:
        """
        {
            "data": {
                "id": "3o7ZeAiCICH5bj1Esg",
                "title": "baby lol GIF",
                "url": "https://giphy.com/gifs/afv-funny-fail-lol-3o7ZeAiCICH5bj1Esg"
            },
            "metadata": {
                "message": "OK",
                "status_code": 200
            }
        }
        """

    @auth
    Scenario: Invalid GIF ID
        Given I send a GET request to "/api/gifs/invalid-id" as an authenticated user
        Then the response status code should be 400
        And the response content should be:
        """
        {
            "data": null,
            "metadata": {
                "message": "Validation error",
                "status_code": 400
            }
        }
        """

    @auth
    Scenario: GIF ID Not found
        Given I send a GET request to "/api/gifs/x4gnt3b9y85l" as an authenticated user
        Then the response status code should be 404
        And the response content should be:
        """
        {
            "data": null,
            "metadata": {
                "message": "Not Found",
                "status_code": 404
            }
        }
        """


    Scenario: Search GIF while not authenticated
        Given I send a GET request to "/api/gifs/x4gnt3b9y85l"
        Then the response status code should be 401
        And the response content should be:
        """
        {
            "error": "Unauthenticated."
        }
        """
