@api_show_user_details

  Feature: Show user details

    Background:
      When I load following file "customer.yaml"

      Scenario:
        When I send a "GET" request to "/api/customers/1/users/1"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "firstName" should exist
        And the JSON node "lastName" should exist
        And the JSON node "email" should exist
        And the JSON node "createdAt" should exist
        And the JSON node "_link" should exist
        And the JSON node "_embedded" should exist

      Scenario:
        When I send a "GET" request to "/api/customers/1/users/1"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "_link.list" should exist
        And the JSON node "_link.list.href" should exist
        And the JSON node "_link.list.href" should contain "/api/customers/1/users"
        And the JSON node "_link.delete" should exist
        And the JSON node "_link.delete.href" should exist
        And the JSON node "_link.delete.href" should contain "/api/customers/1/users/1"

      Scenario:
        When I send a "GET" request to "/api/customers/1/users/1"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "_embedded.address_0" should exist
        And the JSON node "_embedded.address_0.street" should exist
        And the JSON node "_embedded.address_0.city" should exist
        And the JSON node "_embedded.address_0.region" should exist
        And the JSON node "_embedded.address_0.postalCode" should exist
        And the JSON node "_embedded.address_0.phoneNumber" should exist
        And the JSON node "_embedded.customer" should exist
        And the JSON node "_embedded.customer.email" should exist
        And the JSON node "_embedded.customer.organization" should exist
        And the JSON node "_embedded.customer.customerSince" should exist

      Scenario:
        When I send a "GET" request to "/api/customers/1/users/500"
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "message": "Utilisateur introuvable !"
        }
        """

      Scenario:
        When I send a "GET" request to "/api/customers/500/users/1"
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "message": "Client introuvable !"
        }
        """