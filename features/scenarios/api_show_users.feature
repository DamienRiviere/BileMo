@api_show_users

  Feature: Show users

    Background:
      When I load following file 'customer.yaml'

      Scenario:
        When I send a "GET" request to "/api/customers/1/users"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "root[0].firstName" should exist
        And the JSON node "root[0].lastName" should exist
        And the JSON node "root[0].email" should exist
        And the JSON node "root[0].createdAt" should exist
        And the JSON node "root[0]._link" should exist
        And the JSON node "root[0]._embedded" should exist

      Scenario:
        When I send a "GET" request to "/api/customers/1/users"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "root[0]._link.self" should exist
        And the JSON node "root[0]._link.self.href" should exist
        And the JSON node "root[0]._link.self.href" should contain "/api/customers/1/users/1"
        And the JSON node "root[0]._link.first" should exist
        And the JSON node "root[0]._link.first.href" should exist
        And the JSON node "root[0]._link.first.href" should contain "/api/customers/1/users?page=1"
        And the JSON node "root[0]._link.last" should exist
        And the JSON node "root[0]._link.last.href" should exist
        And the JSON node "root[0]._link.last.href" should contain "/api/customers/1/users?page=5"
        And the JSON node "root[0]._link.next" should exist
        And the JSON node "root[0]._link.next.href" should exist
        And the JSON node "root[0]._link.next.href" should contain "/api/customers/1/users?page=2"
        And the JSON node "root[0]._link.prev" should not exist

      Scenario:
        When I send a "GET" request to "/api/customers/1/users?page=2"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "root[0]._link.prev" should exist
        And the JSON node "root[0]._link.prev.href" should exist
        And the JSON node "root[0]._link.prev.href" should contain "/api/customers/1/users?page=1"

      Scenario:
        When I send a "GET" request to "/api/customers/1/users?page=5"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "root[0]._link.next" should not exist

      Scenario:
        When I send a "GET" request to "/api/customers/1/users"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "root[0]._embedded.customer" should exist
        And the JSON node "root[0]._embedded.customer.email" should exist
        And the JSON node "root[0]._embedded.customer.organization" should exist
        And the JSON node "root[0]._embedded.customer.customerSince" should exist

      Scenario:
        When I send a "GET" request to "/api/customers/1/users?page=6"
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "message": "Cette page n'existe pas !"
        }
        """

