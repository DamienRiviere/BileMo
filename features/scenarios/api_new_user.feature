@api_new_user

  Feature: New user

    Background:
      When I load following file 'customer.yaml'

      Scenario:
        When I send a "POST" request to "/api/customers/1/users" with body:
        """
        {
          "firstName": "Damien",
          "lastName": "Dupont",
          "email": "damien.dupont@gmail.com",
          "street": "2 Ma rue",
          "city": "Ma ville",
          "region": "Ma region",
          "postalCode": "99596",
          "phoneNumber": "51215641"
        }
        """
        And the response status code should be 201
        When I send a "POST" request to "/api/customers/1/users" with body:
        """
        {
          "firstName": "Damien",
          "lastName": "Dupont",
          "email": "damien.dupont@gmail.com",
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

      Scenario:
        When I send a "POST" request to "/api/customers/1/users" with body:
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

      Scenario:
        When I send a "POST" request to "/api/customers/6/users"
        Then the response status code should be 404
        And the JSON should be equal to:
        """
        {
            "message": "Client introuvable !"
        }
        """



