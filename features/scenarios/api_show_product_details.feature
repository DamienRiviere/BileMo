@api_show_product_details

  Feature: Show product details

    Background:
      When I load following file "product/product.yaml"

      Scenario:
        When I send a "GET" request to "/api/products/1"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "name" should exist
        And the JSON node "os" should exist
        And the JSON node "dimensions" should exist
        And the JSON node "weight" should exist
        And the JSON node "processor" should exist
        And the JSON node "gpu" should exist
        And the JSON node "ram" should exist
        And the JSON node "colors" should exist
        And the JSON node "ports" should exist
        And the JSON node "_link" should exist
        And the JSON node "_embedded" should exist

    Scenario:
      When I send a "GET" request to "/api/products/1"
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "_link.list" should exist
      And the JSON node "_link.list.href" should exist
      And the JSON node "_link.list.href" should contain "/api/products"

    Scenario:
      When I send a "GET" request to "/api/products/1"
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "_embedded.display" should exist
      And the JSON node "_embedded.display.size" should exist
      And the JSON node "_embedded.display.resolution" should exist
      And the JSON node "_embedded.display.type" should exist

    Scenario:
      When I send a "GET" request to "/api/products/1"
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "_embedded.battery" should exist
      And the JSON node "_embedded.battery.capacity" should exist
      And the JSON node "_embedded.battery.batteryTechnology" should exist
      And the JSON node "_embedded.battery.removableBattery" should exist
      And the JSON node "_embedded.battery.wirelessCharging" should exist
      And the JSON node "_embedded.battery.fastCharge" should exist

    Scenario:
      When I send a "GET" request to "/api/products/1"
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "_embedded.camera" should exist
      And the JSON node "_embedded.camera.megapixels" should exist

    Scenario:
      When I send a "GET" request to "/api/products/1"
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "_embedded.storage_0" should exist
      And the JSON node "_embedded.storage_0.capacity" should exist
      And the JSON node "_embedded.storage_0.price" should exist

    Scenario:
      When I send a "GET" request to "/api/products/51"
      Then the response status code should be 404
      And the response should be in JSON
      And the JSON should be equal to:
      """
      {
          "message": "Produit introuvable !"
      }
      """