# USPS Grumman LLV PCM Repair Landing Page — Design

## Purpose

New, standalone service landing page for Solo Auto Electronics targeting USPS
Grumman LLV PCM & ECM repair. Not a redesign of an existing page — a new
one-off CMS page, built from an approved static mockup, added to the live
Magento 1.9 storefront (`solopcms.com`, theme `rwd/default`).

Source mockup: `USPS Grumman LLV PCM Repair - Solo Auto Electronics (2) (1).html`
(self-extracting bundle, viewed rendered in-browser to confirm content/layout).

## Non-goals

- Not a template for future vehicle-specific pages (one-off; no shared
  partials or reusable section components).
- No changes to nav/menu structure.
- No changes to shared/global CSS files (`styles.css`, `new-style.css`) or
  any PHP/module code.
- No new controller, route, or custom block — pure CMS content.

## Delivery mechanism

**Magento CMS Page**, using the site's standard 1-column page layout.
Because it's the standard layout (not a custom one), the real
`page/html/header.phtml`, `page/html/topmenu.phtml`, and `page/html/footer.phtml`
render exactly as they do on every other page — untouched. This page adds a
content block inside that chrome; it does not reimplement or replace any of it.

| Field | Value |
|---|---|
| URL key | `usps-grumman-llv-pcm-repair` |
| Page title / SEO title | USPS Grumman LLV PCM & ECM Repair |
| Meta title | USPS Grumman LLV PCM Repair \| Solo Auto Electronics |
| Meta description | Grumman LLV PCM testing and repair for no-start, stalling and communication problems. Work directly with an experienced Solo Auto Electronics specialist. |
| Layout | 1 column (default) |
| Nav | Not linked in main menu — direct-link/SEO landing page only |

## Styling — scoped, not shared

**Hard requirement (raised explicitly by project owner): header, menu, and
footer styling/functionality must not be affected.**

- Extract the mockup's own CSS into a new dedicated file:
  `skin/frontend/rwd/default/css/landing-grumman-llv.css`
- Every rule in that file is nested/prefixed under a single wrapper class,
  `.landing-grumman-llv`, applied to the page's outer content container.
  This includes resets — no bare `*`, `body`, `a`, `h1`-`h6`, `.container`,
  etc. Nothing in this file may use a selector capable of matching an
  element inside the header, topmenu, or footer.
- The stylesheet is attached to **this CMS page only**, via that page's
  Custom Design → Layout Update XML field:
  ```xml
  <reference name="head">
    <action method="addItem">
      <type>skin_css</type>
      <name>css/landing-grumman-llv.css</name>
    </action>
  </reference>
  ```
  It is never added to a global layout handle, so no other page loads it.
- Verification step before calling this done: load the page in-browser and
  visually confirm header/topmenu/footer render and behave identically to
  any other page on the site (same nav, same quick-quote sidebar, same
  styling) — i.e. diff against a known-good page, not just eyeball this one.

## Images

Extract the embedded photos from the mockup bundle (hero USPS truck, LLV
product shot, street-scene background) and save them to:
`skin/frontend/rwd/default/images/landing/grumman-llv/`

Referenced via relative paths from `landing-grumman-llv.css` / the page HTML.

## Content

Convert the mockup's sections into the CMS page's HTML content, in order:
1. Hero (headline, subcopy, primary CTAs, 4 trust badges)
2. Common Grumman LLV PCM Problems (6-item list)
3. "A Dedicated Grumman LLV PCM Specialist" (copy + truck image + checklist)
4. "How the Repair Process Works" (4-step process)
5. "Why Repair the Original?" panel (3 supporting points)
6. Contact/quote block (hours, phone, email, ship-to address)
7. Legal disclaimer line: "Solo Auto Electronics is an independent
   automotive electronics repair provider and is not affiliated with or
   endorsed by the United States Postal Service or Grumman." — preserved
   verbatim.

## CTA wiring

- "Call a Grumman LLV Specialist" / "Call (888) 848-0144" → `tel:+18888480144`
- "Email Us About a Grumman LLV PCM" and the quote-block CTA → trigger the
  site's **existing, already-global** quick-quote sidebar
  (`app/design/frontend/rwd/default/template/page/forms/quick-quote.phtml`,
  included sitewide from `footer.phtml`, opened via `class="quick-simple"`)
  instead of a raw `mailto:` link — consistent with how the rest of the site
  captures leads, no new form/PHP required.
- Header's existing "Quick Quote" button is unrelated to this page and
  requires no changes.

## Out of scope / explicitly not touched

- `app/design/frontend/rwd/default/template/page/html/header.phtml`
- `app/design/frontend/rwd/default/template/page/html/topmenu.phtml`
- `app/design/frontend/rwd/default/template/page/html/footer.phtml`
- `skin/frontend/rwd/default/css/styles.css`, `new-style.css`
- Any nav/menu configuration

## Admin access note

No Magento admin credentials available in this session. Deliverables are
prepared as ready-to-paste artifacts (CSS file + images committed to the
theme; HTML content + Layout Update XML snippet provided as text) rather
than the CMS page being created directly. If admin access is shared later,
the page can be created directly instead.
