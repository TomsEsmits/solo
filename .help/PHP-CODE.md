# PHP Code Conventions — solopcms

## Magento 1.x Patterns Used in This Codebase

### Class Naming
Magento 1 uses a flat namespace via class name segments that map to directory paths:
- `TM_FireCheckout_Model_Quote_Address` → `app/code/local/TM/FireCheckout/Model/Quote/Address.php`
- `Aschroder_SMTPPro_Model_Email` → `app/code/local/Aschroder/SMTPPro/Model/Email.php`

### Config-Driven Rewrites
Class rewrites are declared in each module's `etc/config.xml` under `<global><models|blocks|helpers><group><rewrite>`.
Example (TM_FireCheckout):
```xml
<models>
  <sales>
    <rewrite>
      <quote_address>TM_FireCheckout_Model_Quote_Address</quote_address>
    </rewrite>
  </sales>
</models>
```
The rewrite class must extend the original or its parent. Conflicts arise when two modules rewrite the same alias — only the last one loaded wins.

### Observer Pattern
Observer classes implement a method that receives a `Varien_Event_Observer $observer` parameter:
```php
public function orderSaved(Varien_Event_Observer $observer) {
    $order = $observer->getEvent()->getOrder();
    // ...
}
```
Wired in `config.xml` under `<frontend|global|adminhtml><events><event_name><observers>`.

### Models / Collections
```php
$model = Mage::getModel('module_alias/model_name');
$collection = Mage::getModel('catalog/product')->getCollection();
```

### Helpers
```php
$helper = Mage::helper('firecheckout/checkout'); // TM_FireCheckout_Helper_Checkout
```

### Config Values
```php
$value = Mage::getStoreConfig('section/group/field');
```

---

## PHP 7+ Compatibility Shims (local overrides)

### `app/code/local/Varien/Crypt/Mcrypt.php`
Replaces the core `Varien_Crypt_Mcrypt` class (which uses the removed `mcrypt` extension).
- Uses `openssl_encrypt`/`openssl_decrypt` with `AES-256-CBC`
- `init()` method is a no-op (kept for interface compatibility)
- `setKey()` hashes the key with SHA-256 to match AES-256 key length
- `encrypt()`/`decrypt()` prepend/parse a random IV

### `app/code/local/Mage/Core/functions.php`
Patches Magento's core `functions.php` to remove calls to `get_magic_quotes_runtime()`
and `get_magic_quotes_gpc()` which were removed in PHP 7.4+. This is a copy of the
core file with those calls stubbed to return `false`.

---

## Template (.phtml) Conventions
Templates are plain PHP with Magento block methods available via `$this`:
```php
<?php echo $this->getProduct()->getName(); ?>
<?php echo $this->__('Translate string') ?>
<?php echo $this->getChildHtml('block_alias') ?>
```

Direct DB queries in templates are an anti-pattern — keep logic in blocks/models.
Templates in `rwd/default` override `base/default` — check both when debugging a template.

---

## Custom Scripts at Root
- `ajax-call.php` — custom AJAX handler, bypasses Magento bootstrap (caution: no ACL)
- `product-import.php` — standalone product import script (untracked, not in git)
- `info.php` — `phpinfo()` dump — **must be removed before any production deploy**

---

## Things to Watch
- **No Composer autoloading** — Magento 1 uses its own `Varien_Autoload`. Adding libraries means dropping them in `lib/` or using a community autoloader module.
- **Compiler** — `shell/compiler.php` can pre-compile classes for performance. When enabled, changes to `local/` code require re-running the compiler. Check `includes/config.php` to see if compiler is active.
- **Two checkout modules active** — `IWD_Opc` and `TM_FireCheckout` both rewrite checkout. Determine which is actually in use before modifying any checkout-related code.
