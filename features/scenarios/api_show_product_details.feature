@api_show_product_details

  Feature: Show product details

    Background: Load fixtures and log to the API
      When I load following file "customer.yaml"
      When I load following file "product/product.yaml"
      When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "GET" request to "/api/products/1" with body:
      """
        {
        }
      """

      Scenario: Test all nodes
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

    Scenario: Test _link nodes
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "_link.list" should exist
      And the JSON node "_link.list.href" should exist
      And the JSON node "_link.list.href" should contain "/api/products"

    Scenario: Test _embedded.display nodes
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "_embedded.display" should exist
      And the JSON node "_embedded.display.size" should exist
      And the JSON node "_embedded.display.resolution" should exist
      And the JSON node "_embedded.display.type" should exist

    Scenario: Test _embedded.battery nodes
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "_embedded.battery" should exist
      And the JSON node "_embedded.battery.capacity" should exist
      And the JSON node "_embedded.battery.batteryTechnology" should exist
      And the JSON node "_embedded.battery.removableBattery" should exist
      And the JSON node "_embedded.battery.wirelessCharging" should exist
      And the JSON node "_embedded.battery.fastCharge" should exist

    Scenario: Test _embedded.camera nodes
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "_embedded.camera" should exist
      And the JSON node "_embedded.camera.megapixels" should exist

    Scenario: Test _embedded.storage nodes
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON node "_embedded.storage_0" should exist
      And the JSON node "_embedded.storage_0.capacity" should exist
      And the JSON node "_embedded.storage_0.price" should exist

    Scenario: Test with a product who doesn't exist
      When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "GET" request to "/api/products/51" with body:
      """
        {
        }
      """
      Then the response status code should be 404
      And the response should be in JSON
      And the JSON should be equal to:
      """
      {
          "message": "Produit introuvable !"
      }
      """