@api_show_user_details

  Feature: Show user details

    Background: Load fixtures and log to the API
      When I load following file "customer.yaml"
      When I load following file "product/product.yaml"
      When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "GET" request to "/api/customers/1/users/1" with body:
      """
        {
        }
      """

      Scenario: Test all nodes
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "firstName" should exist
        And the JSON node "lastName" should exist
        And the JSON node "email" should exist
        And the JSON node "createdAt" should exist
        And the JSON node "_link" should exist
        And the JSON node "_embedded" should exist

      Scenario: Test _link nodes
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "_link.list" should exist
        And the JSON node "_link.list.href" should exist
        And the JSON node "_link.list.href" should contain "/api/customers/1/users"
        And the JSON node "_link.delete" should exist
        And the JSON node "_link.delete.href" should exist
        And the JSON node "_link.delete.href" should contain "/api/customers/1/users/1"

      Scenario: Test _embedded nodes
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

      Scenario: Test with a user who doesn't exist
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "GET" request to "/api/customers/1/users/500" with body:
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

      Scenario: Test with a customer who doesn't exist
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "GET" request to "/api/customers/500/users/1" with body:
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

      Scenario: Test to access at one user from another customer
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "GET" request to "/api/customers/2/users/51" with body:
        """
          {
          }
        """
        Then the response status code should be 403
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "message": "Vous n'êtes pas autorisé à accéder à cette ressource !"
        }
        """