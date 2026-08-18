# Directory Structure — solopcms

## Project Root
```
/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/
  public_html/          <- Magento root (also git repo root)
  public_html.zip       <- archive copy (not in git)
```

## Magento Root (`public_html/`)
```
public_html/
  app/
    code/
      core/             <- Magento core (READ ONLY — do not edit)
      community/        <- third-party modules
      local/            <- all custom/overriding code (edit here)
    design/
      frontend/
        base/default/   <- Magento base fallback (some direct edits — see GIT.md)
        default/modern/ <- legacy theme (one modified template)
        rwd/default/    <- ACTIVE THEME — primary edit target
    etc/
      local.xml         <- DB creds, session config (gitignored)
      modules/          <- module enable/disable XML files
  blog/                 <- embedded WordPress install
    wp-content/
      themes/expound/   <- active WP theme (modified: functions.php, single.php)
  js/                   <- Magento core JS (do not edit)
  lib/                  <- Magento PHP libraries
  media/                <- uploaded product images (gitignored)
  shell/                <- CLI scripts (indexer.php, compiler.php, log.php, abstract.php)
  skin/
    frontend/
      rwd/default/      <- ACTIVE THEME skin
        css/            <- styles.css (modified), new-style.css (modified)
        js/             <- app.js (modified)
        scss/           <- SCSS source files
        images/
  var/                  <- cache, logs, sessions, reports (gitignored)
  .claude/              <- Claude Code project config (gitignored)
  .help/                <- this documentation folder
  .gitignore
  .htaccess             <- URL rewrites; sets local domain solopcms.test
  cron.php              <- Magento cron entry point
  cron.sh               <- shell wrapper for cron
  index.php             <- Magento front controller
  ajax-call.php         <- custom AJAX handler (modified — in git)
  product-import.php    <- custom product import script (untracked)
  info.php              <- phpinfo() — REMOVE before production deploy
```

## Local Modules (`app/code/local/`)
```
local/
  Aschroder/
    SMTPPro/            <- SMTP relay; etc/config.xml, Model/Email*.php
  GoDataFeed/
    Services/           <- Feed API; Model/Api/, Model/Cart/, Model/Method/
  Mage/
    Core/
      functions.php     <- magic_quotes PHP 7+ patch (single file, no etc/)
  SOAP/
    ShoppingAnalytics/  <- Analytics; Block/, Helper/, Model/Observer.php, etc/
  TM/
    CheckoutFields/     <- etc/config.xml, Model/, Block/, Helper/
    CheckoutSuccess/    <- etc/config.xml, Model/, Block/
    FireCheckout/       <- etc/config.xml, Model/, Block/, Helper/ (largest module)
    OrderAttachment/    <- etc/config.xml, Model/, Block/, Helper/, controllers/
    ReviewReminder/     <- etc/config.xml, Model/Observer.php, cron jobs
  Varien/
    Crypt/
      Mcrypt.php        <- openssl-based drop-in for Varien_Crypt_Mcrypt
```

## Active Theme Files of Note (`app/design/frontend/rwd/default/`)
```
layout/
  local.xml             <- project-level layout overrides (modified)
template/
  catalog/product/
    view.phtml          <- product detail page (modified)
    view-service-only.phtml <- custom service product template
    list/toolbar.phtml  <- category toolbar (modified)
  checkout/
    cart.phtml          <- cart page (modified)
    success.phtml       <- order success page (modified)
  page/
    1column.phtml, 2columns-left.phtml, etc. <- all page layouts (modified)
    custom_column.phtml <- custom front-page layout
    new_custom_product.phtml <- custom product layout
    html/
      header.phtml, footer.phtml, head.phtml, topmenu.phtml (all modified)
      new-header.phtml  <- alternate header template
  cms/                  <- CMS block templates (untracked — new)
```

## Key Config Files
| File | Purpose |
|------|---------|
| `app/etc/local.xml` | DB, cache, session config — gitignored |
| `app/etc/modules/*.xml` | Enable/disable each module |
| `app/design/frontend/rwd/default/layout/local.xml` | Layout overrides for active theme |
| `.htaccess` | Apache rewrites, sets domain to `solopcms.test` |
| `cron.sh` | Cron wrapper — verify path matches server |
