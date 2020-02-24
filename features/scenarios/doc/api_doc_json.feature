@api_doc_json

  Feature:

    Background:

      Scenario: Test API doc in JSON
        When I send a "GET" request to "/api/doc.json"
        Then the response status code should be 200
        And the response should be in JSON