@api_show_products

  Feature: Show products

    Background: Load fixtures and log to the API
      When I load following file "customer.yaml"
      When I load following file "product/product.yaml"
      When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "GET" request to "/api/products" with body:
      """
        {
        }
      """

    Scenario: Test all nodes
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "root[0].name" should exist
      And the JSON node "root[0].os" should exist
      And the JSON node "root[0].dimensions" should exist
      And the JSON node "root[0].weight" should exist
      And the JSON node "root[0].processor" should exist
      And the JSON node "root[0].gpu" should exist
      And the JSON node "root[0].ram" should exist
      And the JSON node "root[0].colors" should exist
      And the JSON node "root[0].ports" should exist
      And the JSON node "root[0]._link" should exist
      And the JSON node "root[0]._embedded" should exist

    Scenario: Test _link nodes
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "root[0]._link.self" should exist
      And the JSON node "root[0]._link.self.href" should exist
      And the JSON node "root[0]._link.self.href" should contain "/api/products/1"
      And the JSON node "root[0]._link.first" should exist
      And the JSON node "root[0]._link.first.href" should exist
      And the JSON node "root[0]._link.first.href" should contain "/api/products?page=1"
      And the JSON node "root[0]._link.last" should exist
      And the JSON node "root[0]._link.last.href" should exist
      And the JSON node "root[0]._link.last.href" should contain "/api/products?page=5"
      And the JSON node "root[0]._link.next" should exist
      And the JSON node "root[0]._link.next.href" should exist
      And the JSON node "root[0]._link.next.href" should contain "/api/products?page=2"

    Scenario: Test _embedded nodes
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "root[0]._embedded.display" should exist
      And the JSON node "root[0]._embedded.battery" should exist
      And the JSON node "root[0]._embedded.camera" should exist
      And the JSON node "root[0]._embedded.storage" should exist

    Scenario: Test _link.prev
      When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "GET" request to "/api/products?page=1" with body:
      """
        {
        }
      """
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "root[0]._link.prev" should not exist
      When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "GET" request to "/api/products?page=2" with body:
      """
        {
        }
      """
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "root[0]._link.prev" should exist

    Scenario: Test _link.next
      When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "GET" request to "/api/products?page=5" with body:
      """
        {
        }
      """
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "root[0]._link.next" should not exist

    Scenario: Test a page who doesn't exist
      When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "GET" request to "/api/products?page=6" with body:
      """
        {
        }
      """
      Then the response status code should be 404
      And the response should be in JSON
      And the JSON should be equal to:
      """
      {
        "message": "Cette page n'existe pas !"
      }
      """