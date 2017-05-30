Installation
============

> **NOTE:** The bundle is compatible with Symfony `2.0` upwards.

1. Download this bundle to your project first. The preferred way to do it is
    to use [Composer](https://getcomposer.org/) package manager:
    
    ``` json
    $ composer require hexim/zcash-bundle
    ```
2. Add this bundle to your application's kernel:
    
    ``` php
    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Hexim\HeximZcashBundle\HeximZcashBundle(),
            // ...
        );
    }
    ```

3. Configure the bundle in your config:
    
    ``` yaml
    # app/config/config.yml
    hexim_zcash:
        rpc_password: "%zcash_rpc_password%"
        rpc_user: "%zcash_rpc_user%"
        rpc_port: "%zcash_rpc_port%"
    ```
    ``` yaml
    # app/config/parameters.yml
    parameters:
        zcash_rpc_password: password
        zcash_rpc_user: user
        zcash_rpc_port: 8282
    ```
    
Usage
=====

In your application controller methods:

```php
public function yourAction(Request $request)
{
        $wallet = $this->get('hexim_zcash.wallet');
        if (!$walletInfo = $wallet->getWalletInfo()) {
            throw new \Exception('Error: ' . $wallet->getError());
        }

        ...
}
```

```php
public function yourAction(Request $request)
{
        $wallet = $this->get('hexim_zcash.wallet');
        $walletInfo = $wallet->getWalletInfo();
        $transactions = $wallet->listTransactions($walletInfo['result']['txcount']);
        
        ...
}
```
```php
public function yourAction(Request $request)
{
            $myAddress = "t1ededed...";

            $zcashUtil = $this->get('hexim_zcash.util');
            if ($data = $zcashUtil->validateAddress($myAddress) {
                if (!$data['result']['isvalid']) {
                    $this->addFlash('error', ' Sorry but this tAddress is not valid zcash address.');
                    return $this->render('any.html.twig', [
                        'route' => 'list',
                        'apiForm' => $form->createView(),
                    ]);
                }
            }

            ...
}
```