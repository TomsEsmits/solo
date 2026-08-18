# Git Workflow — solopcms

## Repository
- **Root:** `public_html/` (Magento webroot is also the repo root)
- **Remote:** `origin` (single remote — URL not confirmed from code alone)
- **Branches:** `main`, `august-development`
- **Current branch:** `august-development`
- **History:** shallow — only one commit visible (`a1cae23f "first commit"`)

## Gitignored Paths (key ones)
```
var/cache/, var/log/, var/report/, var/session/, var/tmp/
includes/src/, includes/config.php
app/etc/local.xml          <- DB credentials live here — never commit
media/tmp/, media/import/
*.zip, *.tar, *.gz
.claude/settings.local.json  <- added during setup (local machine config)
```

## Currently Modified Files (august-development branch)
These are tracked files with uncommitted changes:

**Custom AJAX/root scripts:**
- `ajax-call.php`

**Core overrides (direct edits to core — see warning below):**
- `app/code/core/Mage/Catalog/Block/Product/View/Options/Type/Select.php`

**Base/default template overrides (direct edits):**
- `app/design/frontend/base/default/layout/catalog.xml`
- `app/design/frontend/base/default/template/catalog/category/view.phtml`
- `app/design/frontend/base/default/template/catalog/product/view/options/type/text.phtml`
- `app/design/frontend/base/default/template/catalog/product/view/type/options/configurable.phtml`
- `app/design/frontend/base/default/template/creareseo/logo/schema.phtml`
- `app/design/frontend/base/default/template/page/1column.phtml` (and 2col/3col variants)
- `app/design/frontend/default/modern/template/page/3columns.phtml`

**FireCheckout template:**
- `app/design/frontend/base/default/template/tm/firecheckout/checkout-2columns.phtml`

**Active theme (rwd/default) — primary work area:**
- `app/design/frontend/rwd/default/layout/local.xml`
- `app/design/frontend/rwd/default/template/catalog/product/list/toolbar.phtml`
- `app/design/frontend/rwd/default/template/catalog/product/view-service-only.phtml`
- `app/design/frontend/rwd/default/template/catalog/product/view.phtml`
- `app/design/frontend/rwd/default/template/catalog/product/view/addtocart-new.phtml`
- `app/design/frontend/rwd/default/template/catalog/product/view/media.phtml`
- `app/design/frontend/rwd/default/template/catalog/product/view/options/wrapper.phtml`
- `app/design/frontend/rwd/default/template/checkout/cart.phtml`
- `app/design/frontend/rwd/default/template/checkout/success.phtml`
- `app/design/frontend/rwd/default/template/page/1column.phtml` (and all column variants)
- `app/design/frontend/rwd/default/template/page/custom_column.phtml`
- `app/design/frontend/rwd/default/template/page/forms/contact-us.phtml`
- `app/design/frontend/rwd/default/template/page/forms/front-page-form-redesign.phtml`
- `app/design/frontend/rwd/default/template/page/html/footer.phtml`
- `app/design/frontend/rwd/default/template/page/html/head.phtml`
- `app/design/frontend/rwd/default/template/page/html/header.phtml`
- `app/design/frontend/rwd/default/template/page/html/new-header.phtml`
- `app/design/frontend/rwd/default/template/page/html/topmenu.phtml`
- `app/design/frontend/rwd/default/template/page/new_custom_product.phtml`
- `app/design/frontend/rwd/default/template/reports/product_viewed.phtml`

**Skin files:**
- `skin/frontend/rwd/default/css/new-style.css`
- `skin/frontend/rwd/default/css/styles.css`
- `skin/frontend/rwd/default/js/app.js`

**WordPress blog:**
- `blog/.htaccess`
- `blog/wp-content/themes/expound/functions.php`
- `blog/wp-content/themes/expound/single.php`

## Untracked Files (new, not yet staged)
- `app/design/frontend/base/default/template/catalog/product/view/options/type/faqs-solo.html`
- `app/design/frontend/rwd/default/template/cms/` (directory)
- `app/design/frontend/rwd/default/template/page/testimonials-new.phtml`
- `app/design/frontend/rwd/default/template/page/wordpress/latest-posts-new.phtml`
- `skin/frontend/rwd/default/images/footer-logos.png`
- `product-import.php`

## ⚠️ Warning: Core File Modified
`app/code/core/Mage/Catalog/Block/Product/View/Options/Type/Select.php` has been
directly edited. This is a bad practice — core edits are overwritten on Magento upgrades
and are hard to track. The correct approach is to rewrite this class via `app/code/local/`.
Do not make additional core edits. If further changes are needed to this file, migrate
the customisation to a local rewrite.

## Merge / Branch Notes
- `august-development` diverged from `main` after the initial commit
- All active development is on `august-development`
- `main` appears to be the baseline/production snapshot
- No PR or merge strategy documented — confirm with team before merging
