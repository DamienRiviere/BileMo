@api_delete_user

  Feature: Delete user

    Background:
      When I load following file "customer.yaml"

      Scenario:
        When I send a "DELETE" request to "/api/customers/1/users/1"
        Then the response status code should be 204

      Scenario:
        When I send a "DELETE" request to "/api/customers/1/users/500"
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "message": "Utilisateur introuvable !"
        }
        """

      Scenario:
        When I send a "DELETE" request to "/api/customers/500/users/1"
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "message": "Client introuvable !"
        }
        """


