# Data Flow — solopcms

## Two Main Patterns

### 1. Request / Response
Module handles an inbound HTTP or API request and returns a direct response.
No event system involved — controller receives, model processes, response sent.

### 2. Event / Observer / Cron
Module hooks into Magento's event bus (`Mage::dispatchEvent`) or Magento's cron
scheduler. No direct request handling — side effects triggered by other actions.

---

## Module Classification

### Request / Response
| Module | Entry Point | What It Does |
|--------|------------|--------------|
| `GoDataFeed/Services` | SOAP/REST API endpoints (`Model/Api/`, `Model/Cart/Api.php`) | Exposes product catalogue, cart, customer, and order data to GoDataFeed's feed aggregator. Purely read-only API surface. |
| `TM/FireCheckout` | `/firecheckout` route (custom frontend controller) | Renders the one-page checkout page and handles AJAX checkout steps (address, shipping, payment). Also rewrites several core blocks and helpers (see PHP-CODE.md). |
| `TM/OrderAttachment` | Admin grid + frontend upload endpoint | Handles file upload/download for order attachments. Cron side also present (see below). |

### Event / Observer
| Module | Key Events Observed | What It Does |
|--------|-------------------|--------------|
| `Aschroder/SMTPPro` | `core_email_*` (via model rewrites, not observer XML) | Intercepts all outbound email at the model level and routes through configured SMTP server instead of PHP `mail()`. Rewrites: `core/email`, `core/email_template`, `core/email_queue`. |
| `SOAP/ShoppingAnalytics` | Sales and customer events (11 event hooks) | Injects analytics tracking data into page blocks (`Block/Sa.php`, `Block/Saf.php`). Observer at `Model/Observer.php`. |
| `TM/CheckoutFields` | Checkout + order save events (13 hooks) | Saves custom field values entered at checkout alongside the order. PDF rewrite hooks are commented out. |
| `TM/CheckoutSuccess` | `checkout_onepage_controller_success_action` + 1 cron | Fires post-order logic on the success page. Cron handles deferred post-order tasks. |
| `TM/FireCheckout` | 49 event hooks (highest count) | Wires checkout validation, payment, shipping, and PayPal iframe events into the FireCheckout flow. |
| `TM/ReviewReminder` | `sales_order_save_after` → `orderSaved` observer | Records eligible orders for follow-up. Cron then sends review-request emails at scheduled intervals. |

### Cron
| Module | Cron Count | Purpose |
|--------|-----------|---------|
| `TM/OrderAttachment` | 8 jobs | Scheduled attachment processing/cleanup |
| `TM/ReviewReminder` | 6 jobs | Timed email sends for review requests |
| `TM/CheckoutSuccess` | 1 job | Deferred post-order processing |

Cron is managed via `Aoe_Scheduler` (community module) which provides an admin UI
at System > Scheduler. Standard Magento cron entry: `* * * * * php /path/to/public_html/cron.php`
(shell wrapper also at `cron.sh`).

---

## Core Overrides (not event-based — class rewrites)

These replace Magento core classes entirely via `<rewrite>` in `config.xml`:

| Core Class | Replacement | Reason |
|-----------|------------|--------|
| `core/email` | `Aschroder_SMTPPro_Model_Email` | Route email through SMTP |
| `core/email_template` | `Aschroder_SMTPPro_Model_Email_Template` | Route email through SMTP |
| `core/email_queue` | `Aschroder_SMTPPro_Model_Email_Queue` | Route email through SMTP |
| `sales/quote_address` | `TM_FireCheckout_Model_Quote_Address` | Fix PayPal Express validation |
| `checkout/links` | `TM_FireCheckout_Block_Links` | Replace checkout link block |
| `checkout/cart_sidebar` | `TM_FireCheckout_Block_Cart_Sidebar` | Replace cart sidebar block |
| `paypal/iframe` | `TM_FireCheckout_Block_Paypal_Iframe` | PayPal iframe in FireCheckout |
| `paypal/payflow_link_iframe` | `TM_FireCheckout_Block_Paypal_Payflow_Link_Iframe` | PayPal Payflow in FireCheckout |
| `paypal/payflow_advanced_iframe` | `TM_FireCheckout_Block_Paypal_Payflow_Advanced_Iframe` | PayPal Advanced in FireCheckout |
| `paypal/hosted_pro_iframe` | `TM_FireCheckout_Block_Paypal_Hosted_Pro_Iframe` | PayPal Pro in FireCheckout |
| `checkout/data` (helper) | `TM_FireCheckout_Helper_Checkout` | FireCheckout helper |
| `checkout/url` (helper) | `TM_FireCheckout_Helper_Url` | FireCheckout URL helper |
| `authorizenet/data` (helper) | `TM_FireCheckout_Helper_Authorizenet` | Authorize.net in FireCheckout |

---

## Template Override Flow (rwd/default theme)

```
Request hits Magento -> layout XML loaded (rwd/default/layout/local.xml first,
  then rwd/default/layout/*.xml, then base/default/layout/*.xml as fallback)
  -> blocks instantiated -> templates rendered from rwd/default/template/
  -> fallback to base/default/template/ if not found in rwd/default
```

Many templates are directly modified (see GIT.md for list). When editing a template,
always check if there is also a layout handle in `local.xml` referencing it.
