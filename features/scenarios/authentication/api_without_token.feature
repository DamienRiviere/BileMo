@api_without_token

  Feature: Test API without token
  
    Background:

      Scenario: Test API ShowProducts without token
        When I send a "GET" request to "/api/products"
        Then the response status code should be 401
        And the JSON should be equal to:
        """
        {
          "status": "401 Authentification nécessaire",
          "message": "Aucun token !"
        }
        """

      Scenario: Test API ShowProductDetails without token
        When I send a "GET" request to "/api/products/1"
        Then the response status code should be 401
        And the JSON should be equal to:
        """
        {
          "status": "401 Authentification nécessaire",
          "message": "Aucun token !"
        }
        """

      Scenario: Test API ShowUsers without token
        When I send a "GET" request to "/api/customers/1/users"
        Then the response status code should be 401
        And the JSON should be equal to:
        """
        {
          "status": "401 Authentification nécessaire",
          "message": "Aucun token !"
        }
        """

      Scenario: Test API ShowUserDetails without token
        When I send a "GET" request to "/api/customers/1/users/1"
        Then the response status code should be 401
        And the JSON should be equal to:
          """
          {
            "status": "401 Authentification nécessaire",
            "message": "Aucun token !"
          }
          """

      Scenario: Test API NewUser without token
        When I send a "POST" request to "/api/customers/1/users"
        Then the response status code should be 401
        And the JSON should be equal to:
          """
          {
            "status": "401 Authentification nécessaire",
            "message": "Aucun token !"
          }
          """

      Scenario: Test API DeleteUser without token
        When I send a "DELETE" request to "/api/customers/1/users/1"
        Then the response status code should be 401
        And the JSON should be equal to:
          """
          {
            "status": "401 Authentification nécessaire",
            "message": "Aucun token !"
          }
          """