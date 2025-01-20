Feature: Save GIF as Favorite
    In order to save a GIF to my favorites
    As a logged-in user
    I want to add a GIF to my favorites list

    @auth
    Scenario: Save a GIF as a favorite with valid input
        Given I send a POST request to "/api/favorites" as an authenticated user with body:
        """
        {
            "gif_id": "3o7ZeAiCICH5bj1Esg",
            "alias": "Funny Baby GIF",
            "user_id": "123e4567-e89b-12d3-a456-426614174000"
        }
        """
        Then the response status code should be 201
        And the response content should match JSON:
        """
        {
            "message": "GIF saved as favorite",
            "data": {
                "id": "<string>",
                "alias": "Funny Baby GIF",
                "gif_id": "3o7ZeAiCICH5bj1Esg",
                "user_id": "123e4567-e89b-12d3-a456-426614174000"
            }
        }
        """

    @auth
    Scenario: Save a GIF with a duplicate alias
        Given I send a POST request to "/api/favorites" as an authenticated user with body:
        """
        {
            "gif_id": "26tP3M3i03hoIYL6M",
            "alias": "Funny Baby GIF",
            "user_id": "123e4567-e89b-12d3-a456-426614174000"
        }
        """
        Then the response status code should be 409
        And the response content should be:
        """
        {
            "error": "The alias <Funny Baby GIF> is already in use for another favorite."
        }
        """

    @auth
    Scenario: Save a GIF that is already in favorites
        Given I send a POST request to "/api/favorites" as an authenticated user with body:
        """
        {
            "gif_id": "3o7ZeAiCICH5bj1Esg",
            "alias": "Another Funny GIF",
            "user_id": "123e4567-e89b-12d3-a456-426614174000"
        }
        """
        Then the response status code should be 409
        And the response content should be:
        """
        {
            "error": "The GIF is already saved as a favorite."
        }
        """

    Scenario: Save a GIF as a favorite while unauthenticated
        Given I send a POST request to "/api/favorites" with body:
        """
        {
            "gif_id": "3o7ZeAiCICH5bj1Esg",
            "alias": "Funny Baby GIF",
            "user_id": "123e4567-e89b-12d3-a456-426614174000"
        }
        """
        Then the response status code should be 401
        And the response content should be:
        """
        {
            "error": "Unauthenticated."
        }
        """
