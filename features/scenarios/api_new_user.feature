@api_new_user

  Feature: New user

    Background: Load fixture
      When I load following file 'customer.yaml'

      Scenario: Test to create a new user and after that try to create the same user again
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "POST" request to "/api/customers/1/users" with body:
        """
        {
          "firstName": "Damien",
          "lastName": "Riviere",
          "email": "damien@gmail.com",
          "street": "2 Ma rue",
          "city": "Ma ville",
          "region": "Ma region",
          "postalCode": "99596",
          "phoneNumber": "51215641"
        }
        """
        And the response status code should be 201
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "POST" request to "/api/customers/1/users" with body:
        """
        {
          "firstName": "Damien",
          "lastName": "Riviere",
          "email": "damien@gmail.com",
          "street": "2 Ma rue",
          "city": "Ma ville",
          "region": "Ma region",
          "postalCode": "99596",
          "phoneNumber": "51215641"
        }
        """
        And the response status code should be 400
        And the JSON should be equal to:
        """
        {
            "email": "Cette adresse email est déjà existante, veuillez en choisir une autre !"
        }
        """

      Scenario: Test to create a user with every fields empty
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "POST" request to "/api/customers/1/users" with body:
        """
        {
          "firstName": "",
          "lastName": "",
          "email": "",
          "street": "",
          "city": "",
          "region": "",
          "postalCode": "",
          "phoneNumber": ""
        }
        """
        Then the response status code should be 400
        And the JSON should be equal to:
        """
        {
          "firstName": "Votre prénom ne doit pas être vide !",
          "lastName": "Votre nom ne doit pas être vide !",
          "email": "Votre email ne doit pas être vide !",
          "street": "Votre rue ne doit pas être vide !",
          "city": "Votre ville ne doit pas être vide !",
          "region": "Votre region ne doit pas être vide !",
          "postalCode": "Votre code postal ne doit pas être vide !",
          "phoneNumber": "Votre numéro de téléphone ne doit pas être vide !"
        }
        """

      Scenario: Test to create a user with a customer who doesn't exist
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "POST" request to "/api/customers/10/users" with body:
        """
        {
          "firstName": "Damien",
          "lastName": "Riviere",
          "email": "damien@gmail.com",
          "street": "2 Ma rue",
          "city": "Ma ville",
          "region": "Ma region",
          "postalCode": "99596",
          "phoneNumber": "51215641"
        }
        """
        Then the response status code should be 404
        And the JSON should be equal to:
        """
        {
            "message": "Client introuvable !"
        }
        """

      Scenario: Test to create one user with another customer
        When After authentication on url "/api/login_check" with method "POST" as user "customer@gmail.com" with password "password", I send a "POST" request to "/api/customers/2/users" with body:
        """
        {
          "firstName": "Damien",
          "lastName": "Riviere",
          "email": "damien@gmail.com",
          "street": "2 Ma rue",
          "city": "Ma ville",
          "region": "Ma region",
          "postalCode": "99596",
          "phoneNumber": "51215641"
        }
        """
        Then the response status code should be 403
        And the JSON should be equal to:
        """
        {
            "message": "Vous n'êtes pas autorisé à créer cette ressource !"
        }
        """



