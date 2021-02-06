<?php
    include_once 'inc/amocrm-api-php-master/vendor/autoload.php';//Библиотека
    include_once 'inc/amocrm-api-php-master/examples/token_actions.php';//Функции для получения и сохранения токена
    $clientId = '';
    $clientSecret = '';
    $redirectUri = '';
    $apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

    $accessToken = getToken();
    $apiClient->setAccessToken($accessToken)
        ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
        ->onAccessTokenRefresh(
            function (League\OAuth2\Client\Token\AccessTokenInterface $accessToken, string $baseDomain) {
                saveToken(
                    [
                        'accessToken' => $accessToken->getToken(),
                        'refreshToken' => $accessToken->getRefreshToken(),
                        'expires' => $accessToken->getExpires(),
                        'baseDomain' => $baseDomain,
                    ]
                );
            }
        );

    $contact = new AmoCRM\Models\ContactModel();
    $contact->setName('Example');//Заполняем данными наш контакт
    $contactModel = $apiClient->contacts()->addOne($contact);//Добавляем его в crm готово