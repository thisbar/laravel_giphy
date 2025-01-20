Feature: Search GIFs
    In order to get the found GIFs list
    As a logged-in user
    I want to search through GIFs with pagination and query

    @auth
    Scenario: Search GIFs with valid query
        Given I send a GET request to "/api/gifs/search?query=funny&limit=5&offset=0" as an authenticated user
        Then the response status code should be 200
        And the response content should be:
        """
        {
          "data": [
            {
              "id": "3o7ZeAiCICH5bj1Esg",
              "title": "baby lol GIF",
              "url": "https://giphy.com/gifs/afv-funny-fail-lol-3o7ZeAiCICH5bj1Esg"
            },
            {
              "id": "26tP3M3i03hoIYL6M",
              "title": "Kid Lol GIF",
              "url": "https://giphy.com/gifs/afv-funny-fail-lol-26tP3M3i03hoIYL6M"
            }
          ],
          "metadata": {
            "message": "OK",
            "status_code": 200
          },
          "pagination": {
            "count": 2,
            "total_count": 500,
            "offset": 0
          }
        }
        """

    @auth
    Scenario: Search GIFs with no results
        Given I send a GET request to "/api/gifs/search?query=no-results&limit=5&offset=0" as an authenticated user
        Then the response status code should be 200
        And the response content should be:
        """
        {
          "data": [],
          "metadata": {
            "message": "OK",
            "status_code": 200
          },
          "pagination": {
            "count": 0,
            "total_count": 0,
            "offset": 0
          }
        }
        """

    Scenario: Search GIFs while not authenticated
        Given I send a GET request to "/api/gifs/search?query=funny&limit=5&offset=0"
        Then the response status code should be 401
        And the response content should be:
        """
        {
            "error": "Unauthenticated."
        }
        """
