@api_show_products

  Feature: Show products

    Background:
      When I load following file "product/product.yaml"

    Scenario:
      When I send a "GET" request to "/api/products"
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

    Scenario:
      When I send a "GET" request to "/api/products"
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
      And the JSON node "root[0]._link.prev" should not exist

    Scenario:
      When I send a "GET" request to "/api/products"
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "root[0]._embedded.display" should exist
      And the JSON node "root[0]._embedded.battery" should exist
      And the JSON node "root[0]._embedded.camera" should exist
      And the JSON node "root[0]._embedded.storage" should exist

    Scenario:
      When I send a "GET" request to "/api/products?page=2"
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "root[0]._link.prev" should exist

    Scenario:
      When I send a "GET" request to "/api/products?page=5"
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "root[0]._link.next" should not exist

    Scenario:
      When I send a "GET" request to "/api/products?page=6"
      Then the response status code should be 404
      And the response should be in JSON
      And the JSON should be equal to:
      """
      {
        "message": "Cette page n'existe pas !"
      }
      """