@api_delete_user

  Feature: Delete user

    Background:
      When I load following file "customer.yaml"

      Scenario: Test to delete a user
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "DELETE" request to "/api/customers/1/users/1" with body:
        """
        {
        }
        """
        Then the response status code should be 204

      Scenario: Test to delete a user who doesn't exist
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "DELETE" request to "/api/customers/1/users/500" with body:
        """
        {
        }
        """
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "message": "Utilisateur introuvable !"
        }
        """

      Scenario: Test to delete a user from a customer who doesn't exist
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "DELETE" request to "/api/customers/500/users/1" with body:
        """
        {
        }
        """
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "message": "Client introuvable !"
        }
        """

      Scenario: Test to delete a user from another customer
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "DELETE" request to "/api/customers/2/users/51" with body:
        """
        {
        }
        """
        Then the response status code should be 403
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "message": "Vous n'êtes pas autorisé à supprimer cette ressource !"
        }
        """


