# Architecture — solopcms

## Platform
- **Magento 1.9 CE** (installed 2014-12-05, still active)
- **Git repo root:** `public_html/` — single remote `origin`, branches `main` and `august-development` (current)
- **Local domain:** `solopcms.test`

## Hosting Stack
- **Web server:** Apache (`.htaccess` rewrites active, `mod_rewrite` required)
- **Database:** MySQL — host `localhost`, dbname `solo`, table prefix: none
- **PHP:** Two local shims exist for PHP 7+ compatibility:
  - `app/code/local/Varien/Crypt/Mcrypt.php` — replaces deprecated `mcrypt` with `openssl` (AES-256-CBC)
  - `app/code/local/Mage/Core/functions.php` — patches `magic_quotes` removal for PHP 7+
  - _Exact PHP version not determinable from code alone — check server phpinfo_
- **Sessions:** File-based (`session_save: files` in `local.xml`)
- **Cache:** File-based — `Cm_RedisSession` is present but **disabled** (`active: false`)
- **Embedded WordPress:** `/blog/` subdirectory runs WordPress (theme: `expound`)

## Active Theme
- **Package:** `rwd` / **Theme:** `default`
- Paths: `app/design/frontend/rwd/default/` and `skin/frontend/rwd/default/`
- Parent: none (standalone — `<parent />` is empty in `theme.xml`)
- Fallback chain: `rwd/default` → `base/default`
- Custom CSS: `skin/frontend/rwd/default/css/new-style.css`, `styles.css`
- Custom JS: `skin/frontend/rwd/default/js/app.js`
- SCSS source: `skin/frontend/rwd/default/scss/`

## Custom Layouts (defined in `app/etc/local.xml`)
- `page/custom_column.phtml` — "Custom Layout (New Front Page)"
- `page/new_custom_product.phtml` — "New product template"

## Code Pool Structure
```
app/code/
  local/         <- all live customisations
    Aschroder/SMTPPro        — SMTP email relay (model rewrites)
    GoDataFeed/Services      — Product/order feed API (SOAP/REST)
    Mage/Core/               — Core PHP 7+ compat patch
    SOAP/ShoppingAnalytics   — Analytics pixel injection
    TM/CheckoutFields        — Custom checkout fields
    TM/CheckoutSuccess       — Post-order success page logic
    TM/FireCheckout          — One-page checkout (replaces standard)
    TM/OrderAttachment       — File attachments on orders
    TM/ReviewReminder        — Automated review-request emails
    Varien/Crypt/            — PHP 7+ mcrypt replacement
  community/     <- third-party modules (unmodified)
  core/          <- Magento core (DO NOT EDIT)
```

## Notable Third-Party (Community) Modules
| Module | Purpose | Status |
|--------|---------|--------|
| `Aoe_Scheduler` | Enhanced cron management UI | active |
| `Apptrian_Minify` | JS/CSS minification | active |
| `Cm_RedisSession` | Redis session store | **disabled** |
| `Creare_CreareSeoCore/Sitemap` | SEO + sitemap | active |
| `Creativestyle_CheckoutByAmazon` | Amazon Pay | active |
| `Ess_M2ePro` | eBay/Amazon marketplace connector | active |
| `Grizzly_MassEmail` | Bulk email campaigns | active |
| `IWD_Opc` / `IWD_All` | IWD one-page checkout | active |
| `MSP_FlatShipping5` | Flat-rate shipping | active |
| `Phoenix_Moneybookers` | Skrill payment | active |
| `Raveinfosys_Deleteorder` | Admin: delete orders | active |
| `Studioforty9_Recaptcha` | reCAPTCHA | active |
| `Sumo_Sumo` | Sumo marketing tools | active |
| `Yotpo_Yotpo` | Yotpo reviews widget | active |

## Potential Conflict
Both `IWD_Opc` and `TM_FireCheckout` are marked active. Both replace standard one-page checkout.
Verify which is live: System > Configuration > FireCheckout in admin, or test the checkout URL directly.
