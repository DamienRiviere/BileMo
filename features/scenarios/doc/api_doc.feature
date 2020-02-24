@api_doc

  Feature:

    Background:

      Scenario: Test API doc
        When I send a "GET" request to "/api/doc"
        Then the response status code should be 200