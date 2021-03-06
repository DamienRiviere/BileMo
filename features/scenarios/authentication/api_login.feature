@api_login_check

  Feature: API login check

    Background: Load fixture
      When I load following file "customer.yaml"

      Scenario: Test to login in API with invalid credentials
        When authentication on url "/api/login_check" with method "POST" as user "test@gmail.com" with password "test"
        Then the response status code should be 401
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "code": 401,
            "message": {
                "status": "401 Non autorisé",
                "message": "Identifiants incorrects, veuillez entrer correctement votre email et votre mot de passe !"
            }
        }
        """

      Scenario: Test to login in API with valid credentials
        When authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password"
        Then the response status code should be 200