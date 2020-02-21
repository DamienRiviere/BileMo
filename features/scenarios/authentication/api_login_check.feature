@api_login_check

Feature: Test API login check

  Background:

  Scenario:
    When I send a "POST" request to "/api/login_check" with body:
        """
        {
            "username": "test@gmail.com",
            "password": "password"
        }
        """
    Then the response status code should be 401
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
